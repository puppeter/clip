#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import sys
import os 
import re

class plugin_lt(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)

    def process(self, options, args):   
        # checkparam    
        password = options['p']
        if (options['P'] == None):
            port=self.ssh_port
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

            ip_arr=[] 
            if (options['a'] != None):
                ip_arr=options['a'].split(",")
                if(len(ip_arr) >=2): 
                    for i in ip_arr:
                        if(len(i.split("."))== 4):
                            ip_arr.append(str(i))
                else:
                    ip_arr.append(ipr['0'])
            

            f=open(filename,"r")
            for ip in f:
                ipaddress=str(ip.strip())
                if (options['r'] != None) and (ipaddress in options['r']):
                    continue
                else:
                    ip_arr.append(ipaddress)
                    #self.scp_cmd(filename,password,username,ipaddress,path,port,options)
            f.close()

            if options['w'] == True:
                import threading
                for ip in ip_arr:
                    t=threading.Thread(target=self.scp_cmd,args=(filename,password,username,ip,path,port,options))
                    t.start()
            else:
                for ip in ip_arr:
                    self.scp_cmd(filename,password,username,ip,path,port,options)

        else: 

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
            
            ip_arr=[]
            if (options['a'] != None): 
                ip_arr=options['a'].split(",")
                if(len(ip_arr) >=2): 
                    for i in ip_arr:
                        if(len(i.split("."))== 4):
                            ip_arr.append(str(i))
                            #self.ssh_cmd(str(i),password,command,user,port,options)
                else:
                    ip_arr.append(str(ip_arr[0]))
                    #self.ssh_cmd(str(ip_arr[0]),password,command,user,port,options)
                    
                
            f=open(ip_file,"r")
            for ip in f:
                ipaddress=str(ip.strip())
                if (options['r'] != None) and (ipaddress in options['r']):
                    continue
                else:
                    #self.ssh_cmd(ipaddress,password,command,user,port,options)
                    ip_arr.append(ipaddress)
            f.close()

            if options['w'] == True:
                import threading
                for ip in ip_arr:
                    t=threading.Thread(target=self.ssh_cmd,args=(ip,password,command,user,port,options))
                    t.start()
            else:
                for ip in ip_arr:
                    self.ssh_cmd(ip,password,command,user,port,options)


        sys.exit(0)
        

    def ssh_cmd(self, host,password,command, user,port,options):
        if options['w'] != True:
            print "\033[0;36;40m\033[0;32;40m =============== \033[0;33;40m"+host+" \033[0;32;40m===============\033[0m\n"

        if password == 'null':
            cmd = 'ssh -p '+port+" "+host + ' ' + command  
        else:
            shPath = self.root_path+'/lib/tiny_expect.exp'
            cmd = shPath + ' ' + '"' + command + '"' + ' ' + port + ' ' + password+ ' ' + user + '@' + host 

        if (options['d'] == True):
            print cmd
            sys.exit(0)
        os.system(cmd)

    
    def scp_cmd(self, filename, password, username, host, path,port,options):
        if options['w'] != True:
            print "\033[0;36;40m\033[0;32;40m =============== \033[0;33;40m"+host+" \033[0;32;40m===============\033[0m\n"

        if password == 'null':
            shpath = self.root_path+'/lib/tiny_expect_scp.exp'
            cmd = shpath + ' ' + '"' + password + '"' + ' ' + '"' + filename + ' ' + username + '@' + host +"#"+port +':' + path + '"'
        else:
            cmd = 'scp ' + password + '"' + ' ' + '"' + filename + ' ' + username + '@' + host +"#"+port+':' + path + '"'

        if (options['d'] == True):
            print cmd
            sys.exit(0)
        os.system(cmd)
