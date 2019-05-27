import ftplib as fl

ftps = fl.FTP('ftp.epizy.com')

ftps.login(user='epiz_23186456', passwd='r2kp1h07')
"""
    OmegaLarasFractur89
    
"""


def dir_ls():
    ftps.dir()

def cd_to_dir():
    list_f = ftps.dir()

    ftps.cwd(dirname)

while True:
    cmd = input('ftp >>> ')
    if cmd == 'dir' or cmd == 'ls':
        dir_ls()
    elif cmd == 'cd':
        cd_to_dir()