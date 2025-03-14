#!/bin/bash

# Database credentials
DB_USER="root"
DB_PASS="quizfi"
DB_NAME="quizfi"

# Function to check if a rule exists
rule_exists() {
    sudo iptables -C FORWARD "$@" 2>/dev/null
    return $?
}

# Remove existing rules (ACCEPT and DROP/REJECT) before adding new ones
clear_existing_rules() {
    local ip=$1
    # Remove all matching rules (ACCEPT and DROP/REJECT) for the IP
    sudo iptables -D FORWARD -s "$ip" -o end0 -j ACCEPT 2>/dev/null
    sudo iptables -D FORWARD -s "$ip" -o end0 -j DROP 2>/dev/null
    sudo iptables -D FORWARD -s "$ip" -o end0 -j REJECT 2>/dev/null
}

# Function to ensure only unique rules are added
add_unique_rule() {
    local ip=$1
    local action=$2

    if ! rule_exists -s "$ip" -o end0 -j "$action"; then
        sudo iptables -I FORWARD -s "$ip" -o end0 -j "$action"
    fi
}

# Log file path
log_file="/var/log/quizfi_access_log.log"

# Fetch active students with duration > 0
mysql -u$DB_USER -p$DB_PASS -D$DB_NAME -e "
SELECT user_id, duration, ip_address, status 
FROM students 
WHERE status = 'active' AND duration > 0;
" | while read userId duration ipAddress status; do
    if [[ "$userId" != "user_id" ]]; then # skip column names
        # Skip empty or NULL IP addresses
        if [[ -z "$ipAddress" || "$ipAddress" == "NULL" ]]; then
            echo "Skipping User ID: $userId because IP address is NULL or empty" >> $log_file
            continue
        fi

        # Remove existing ACCEPT or DROP/REJECT rules for the user
        clear_existing_rules "$ipAddress"

        # Add ACCEPT rule for active users, ensuring no duplicates
        add_unique_rule "$ipAddress" "ACCEPT"

        echo "ALLOWED: $ipAddress - User ID: $userId - Duration: $duration - Status: $status" >> $log_file
    fi
done

# Block inactive students or those with duration <= 0
mysql -u$DB_USER -p$DB_PASS -D$DB_NAME -e "
SELECT user_id, duration, ip_address, status 
FROM students 
WHERE status = 'inactive' OR duration <= 0;
" | while read userId duration ipAddress status; do
    if [[ "$userId" != "user_id" ]]; then # skip column names
        # Skip empty or NULL IP addresses
        if [[ -z "$ipAddress" || "$ipAddress" == "NULL" ]]; then
            echo "Skipping User ID: $userId because IP address is NULL or empty" >> $log_file
            continue
        fi

        # Remove existing ACCEPT or DROP/REJECT rules for the user
        clear_existing_rules "$ipAddress"

        # Add DROP rule for inactive users, ensuring no duplicates
        add_unique_rule "$ipAddress" "DROP"

        echo "DISALLOWED: $ipAddress - User ID: $userId - Duration: $duration - Status: $status" >> $log_file
    fi
done

# Save the iptables rules to make them persistent across reboots
sudo iptables-save > /etc/iptables/rules.v4
