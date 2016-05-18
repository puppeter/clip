#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7
from plugin_base import plugin_base
import sys
import os 

class plugin_lt(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)

    def process(self, options, args):   
        # checkparam    
        password = options['p']
        if (options['P'] == None):
            port="22"
        else:
            port=options['P']

        if (options['f'] != False):
            self.checkparam("scp",options,args)
            filename = args[0] 
            tmpstr = args[1] 
            tmpary=tmpstr.split('@')
            username = tmpary[0]
            tary=tmpary[1].split(':')
            ip_file=tary[0]
            path=tary[1]
           
            if os.path.exists(ip_file) != True:
                print "IP FileName Not Exits !"
                sys.exit(1)
            
            if os.path.exists(filename) != True:
                print "Filename Not Exits !"
                sys.exit(1)
            f=open(ip_file,"r")
            
            if (options['a'] != None):
                ip_arr=options['a'].split(",")
                if(len(ip_arr) >=2): 
                    for i in ip_arr:
                        if(len(i.split("."))== 4):
                            self.scp_cmd(filename,password,username,str(i),path,port,options)
                else:
                    self.scp_cmd(filename,password,username,str(ipr['0']),path,port,options)
            
            for ip in f:
                self.scp_cmd(filename,password,username,ip.strip(),path,port,options)

            f=open(filename,"r")
            for ip in f:
                ipaddress=str(ip.strip())
                if (options['r'] != None) and (ipaddress in options['r']):
                    continue
            else:
                self.scp_cmd(filename,password,username,ipaddress,path,port,options)
            f.close()

        # parameter
        self.checkparam("ssh",options,args)
        tmpstr = args[0]
        command= args[1]
        tmp_array = tmpstr.split('@')
        user=tmp_array[0]
        ip_file=tmp_array[1]

        if os.path.exists(ip_file) != True:
            print "IP FileName Not Exits !"
            sys.exit(1)

        if (options['a'] != None): 
            ip_arr=options['a'].split(",")
            if(len(ip_arr) >=2): 
                for i in ip_arr:
                    if(len(i.split("."))== 4):
                        self.ssh_cmd(str(i),password,command,user,port,options)
            else:
                self.ssh_cmd(str(ip_arr[0]),password,command,user,port,options)
                
            
        f=open(ip_file,"r")
        for ip in f:
            ipaddress=str(ip.strip())
            if (options['r'] != None) and (ipaddress in options['r']):
                continue
            else:
                self.ssh_cmd(ipaddress,password,command,user,port,options)
        f.close()

    def ssh_cmd(self, host,passwd,command, user,port,options):
        #tiny_expect.exp "uptime" 22 temp@@pwd  root@10.149.29.175
        print "\033[0;36;40m\033[0;32;40m =============== \033[0;33;40m"+host+" \033[0;32;40m===============\033[0m\n"
        shPath = self.root_path+'/lib/tiny_expect.exp'
        command=command.replace('ipaddress',host)
        cmd = shPath + ' ' + '"' + command + '"' + ' ' + port + ' ' + passwd + ' ' + user + '@' + host 
        if (options['d'] == True):
            print cmd
        os.system(cmd)
    
    def scp_cmd(self, filename, password, username, host, path,port,options):
        print "\033[0;36;40m\033[0;32;40m =============== \033[0;33;40m"+host+" \033[0;32;40m===============\033[0m\n"
        shpath = self.root_path+'/lib/tiny_expect_scp.exp'
        if port != "22":
            cmd = shpath + ' ' + '"' + password + '"' + ' ' + '"' + filename + ' ' + username + '@' + host +"#"+port +':' + path + '"'
        else: 
            cmd = shpath + ' ' + '"' + password + '"' + ' ' + '"' + filename + ' ' + username + '@' + host +':' + path + '"'
        if (options['d'] == True):
            print cmd
        os.system(cmd)
