#!/bin/bash

# Database credentials
DB_USER="root"
DB_PASS="quizfi"
DB_NAME="quizfi"


# Declare associative arrays for tracking processed IPs
declare -A processedActive
declare -A processedInactive

# Function to check if a rule exists
rule_exists() {
    sudo iptables -C FORWARD "$@" 2>/dev/null
    return $?
}

# Remove all matching rules (ACCEPT, DROP, and REJECT) for the given IP
clear_existing_rules() {
    local ip=$1
    # Loop until no ACCEPT rule remains
    while sudo iptables -C FORWARD -s "$ip" -o end0 -j ACCEPT 2>/dev/null; do
        sudo iptables -D FORWARD -s "$ip" -o end0 -j ACCEPT 2>/dev/null
    done
    # Loop until no DROP rule remains
    while sudo iptables -C FORWARD -s "$ip" -o end0 -j DROP 2>/dev/null; do
        sudo iptables -D FORWARD -s "$ip" -o end0 -j DROP 2>/dev/null
    done
    # Loop until no REJECT rule remains
    while sudo iptables -C FORWARD -s "$ip" -o end0 -j REJECT 2>/dev/null; do
        sudo iptables -D FORWARD -s "$ip" -o end0 -j REJECT 2>/dev/null
    done
}

# Function to add a unique rule (only if not present)
add_unique_rule() {
    local ip=$1
    local action=$2

    if ! rule_exists -s "$ip" -o end0 -j "$action"; then
        sudo iptables -I FORWARD -s "$ip" -o end0 -j "$action"
    fi
}

# Log file path
log_file="/var/log/quizfi_access_log.log"

# Process active students (status 'active' and duration > 0)
mysql -u$DB_USER -p$DB_PASS -D$DB_NAME -e "
SELECT user_id, duration, ip_address, status
FROM students
WHERE status = 'active' AND duration > 0;
" | while read userId duration ipAddress status; do
    # Skip header row and ensure valid IP
    if [[ "$userId" == "user_id" || -z "$ipAddress" || "$ipAddress" == "NULL" ]]; then
        echo "Skipping User ID: $userId due to missing IP" >> "$log_file"
        continue
    fi

    # Process each active IP only once
    if [[ -n "${processedActive[$ipAddress]}" ]]; then
        continue
    fi
    processedActive["$ipAddress"]=1

    # Clear existing rules for this IP
    clear_existing_rules "$ipAddress"

    # Add an ACCEPT rule for active users
    add_unique_rule "$ipAddress" "ACCEPT"
    echo "ALLOWED: $ipAddress - User ID: $userId - Duration: $duration - Status: $status" >> "$log_file"
done

# Process inactive students (status 'inactive' or duration <= 0)
mysql -u$DB_USER -p$DB_PASS -D$DB_NAME -e "
SELECT user_id, duration, ip_address, status
FROM students
WHERE status = 'inactive' OR duration <= 0;
" | while read userId duration ipAddress status; do
    # Skip header row and ensure valid IP
    if [[ "$userId" == "user_id" || -z "$ipAddress" || "$ipAddress" == "NULL" ]]; then
        echo "Skipping User ID: $userId due to missing IP" >> "$log_file"
        continue
    fi

    # Process each inactive IP only once
    if [[ -n "${processedInactive[$ipAddress]}" ]]; then
        continue
    fi
    processedInactive["$ipAddress"]=1

    # Clear existing rules for this IP
    clear_existing_rules "$ipAddress"

    # Add a DROP rule for inactive users
    add_unique_rule "$ipAddress" "DROP"
    echo "DISALLOWED: $ipAddress - User ID: $userId - Duration: $duration - Status: $status" >> "$log_file"
done

# Save iptables rules to make them persistent across reboots
sudo iptables-save > /etc/iptables/rules.v4
