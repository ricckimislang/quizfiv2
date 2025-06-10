
QuizFi is an innovative Wi-Fi hotspot system that rewards students with internet access for answering quiz questions correctly. Designed for offline-first access, QuizFi turns learning into a gateway to connectivity.

## Features 🚀

- 📡 Local captive portal powered by Orange Pi One
- 🧠 Quiz-based internet access (Earn Wi-Fi time by answering questions)
- 🔐 Session-based authentication
- 🗃️ PHP + MySQL backend
- ⚡ Fast and lightweight frontend using HTML/CSS
- 🔌 Auto DHCP & network bridging for seamless connectivity

## Tech Stack 🛠️

- PHP & MySQL
- Orange Pi One (Debian 12, systemd-networkd)
- TP-Link EAP110 AP
- Custom captive portal
- HTML, Bootstrap/Tailwind (frontend)

## Setup Instructions ⚙️

1. Clone this repository to your Orange Pi.
2. Set up the MySQL database (`quizfi.sql`).
3. Configure `systemd-networkd` for LAN/WAN bridging.
4. Launch the PHP server (`php -S 192.168.0.1:80 -t public` or use Nginx).
5. Connect a device to the QuizFi hotspot and take a quiz to unlock Wi-Fi!

Riccki Rejee Mislang — Full Stack Web Developer 
Email: codingriccki@gmail.com
