#!/usr/bin/env bash

set -o errexit # Exit script when a command exits with non-zero status.
set -o errtrace # Exit on error inside any functions or sub-shells.
set -o nounset # Exit script on use of an undefined variable.
set -o pipefail # Return exit status of the last command in the pipe that exited with a non-zero exit code

# Depending on environment this script might need to be called using `sudo this-script.sh`

function print_status() {
    echo " -----> $@"
}

print_status 'Adding Chrome public SSL key'
wget -q -O - 'https://dl-ssl.google.com/linux/linux_signing_key.pub' | apt-key add -

# FIXME: Add check to only add URL if it is not present
# FIXME: Add check to add "[arch=amd64]" for x86_64 architecture
print_status 'Creating install list'
sh -c 'echo "deb http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list'

print_status 'Updating apt'
apt-get update

print_status 'Fix any broken dependecies'
apt-get --fix-broken install

print_status 'Installing Chrome'
apt-get install google-chrome-stable --yes

print_status 'Fix NSS version mismatch'
sudo apt-get install --reinstall libnss3

