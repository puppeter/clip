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
                arr=options['a'].split(",")
                if(len(arr) >=2): 
                    for i in ip_arr:
                        if(len(i.split("."))== 4):
                            arr.append(str(i))
                else:
                    ip_arr.append(ipr['0'])
            

            f=open(ip_file,"r")
            for ip in f:
                ipaddress=str(ip.strip())
                if (options['r'] != None) and (ipaddress in options['r']):
                    continue
                else:
                    self.scp_cmd(filename,password,username,ipaddress,path,port,options)
            f.close()
            
            if options['w'] == True:
                import threading
                for ip in ip_arr:
                    t=threading.Thread(target=self.scp_cmd,args=(filename,password,username,ip,path,port,options))
                    t.start()
            else:
                for ip in ip_arr:
                    self.scp_cmd(filename,password,username,ipaddress,path,port,options)
            

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
                else:
                    ip_arr.append(str(ip_arr[0]))
                    
                
            f=open(ip_file,"r")
            for ip in f:
                ipaddress=str(ip.strip())
                if (options['r'] != None) and (ipaddress in options['r']):
                    continue
                else:
                    ip_arr.append(ipaddress)
            f.close()
            
            output=[]
            if options['w'] == True:
                import threading
                for ip in ip_arr:
                    t=threading.Thread(target=self.ssh_cmd,args=(ip,password,command,user,port,options))
                    t.start()
            else:
                for ip in ip_arr:
                    if options['j'] == True:
                        output.append(ip)
                        output.append(self.ssh_cmd(ip,password,command,user,port,options))
                    else:
                        self.ssh_cmd(ip,password,command,user,port,options)

            if options['j'] == True:
                import json             
                print json.dumps(output)


        sys.exit(0)
