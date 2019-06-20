# ===================================================
# APACHE SERVER + PHP + MYSQL etc....
# ===================================================

# Create containers (from images [davidecesarano/docker-compose-lamp] and [mariadb:latest])
# based on Ubuntu 16.04
docker-compose up -d

# Start container (if not started)
docker start webCC2
docker start mysqlCC2 (does not work for the moment with my windows 10 machine)

# open a terminal window
docker exec -it webCC2 /bin/bash


# ------------------------------------------
# Commands to execute at the linux root
# ------------------------------------------

# Update package list and install sudo
su -
apt-get update
apt-get install sudo

# Get install tools and dependencies (especially python3, but it is already installed : just an upgrade here)
sudo apt-get install -y software-properties-common

# Install gcc (for C files, but it is already installed : just an upgrade here)
sudo apt-get install -y gcc

# Install Java (for JAVA files)
sudo add-apt-repository ppa:linuxuprising/java
sudo apt-get update
sudo apt install oracle-java11-installer -y
sudo apt install oracle-java11-set-default -y

# Node JS interpreter (already installed)

# PHP interpreter (already installed)

