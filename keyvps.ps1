# setup-ssh.ps1
# Jalankan script ini SEKALI untuk setup awal

# 1. Generate SSH Key Pair jika belum ada
$sshDir = "$env:USERPROFILE\.ssh"
$privateKey = "$sshDir\id_rsa"
$publicKey = "$sshDir\id_rsa.pub"

if (-not (Test-Path -Path $privateKey)) {
    Write-Host "Generating new SSH key pair..."
    ssh-keygen -t rsa -b 4096 -f $privateKey -N '""'
}

# 2. Copy public key ke server (akan diminta password sekali)
Write-Host "Copying public key to server..."
Get-Content $publicKey | ssh -p 22 root@217.15.165.147 `
"mkdir -p ~/.ssh && `chmod 700 ~/.ssh && `cat >> ~/.ssh/authorized_keys && `chmod 600 ~/.ssh/authorized_keys"

# 3. Setelah ini, bisa login tanpa password
Write-Host "Setup completed! Now you can connect with:"
Write-Host "ssh -p 22 root@217.15.165.147"
