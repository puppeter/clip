#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7
from plugin_base import plugin_base
import sys
import os 

class plugin_ssh(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)

    def process(self, options, args):   
        # checkparam
        self.checkparam("ssh",options,args)
        # parameter
        password = options['p']
        tmpstr = args[0]
        command= args[1]
        tmp_array = tmpstr.split('@')
        user=tmp_array[0]
        ipaddress=tmp_array[1].strip().split('.')
        

        # dim
        if (options['P'] == None):
            port=self.ssh_port
        else:
            port=options['P']

        if len(ipaddress) != 4: 
            options['q']=tmp_array[1].strip().split('.')[0]
            ret=self.get_ip_data(options,args,"cstring")

            ip_arr=[]
            # for -append 
            if (options['a'] != False):
                ret=self.append_data(options,ret,"ssh")
            if (options['l'] != None):
                ret=self.output_limit(ret,options['l'])

            if (options['r'] != None):
                for ip in ret:
                    if ip in options['r']:
                        continue
                    else: 
                        ip_arr.append(ip)
            else:
                    ip_arr=ret
            
            if options['w'] ==True:
                self.check_worker_count(ip_arr)
                import threading
                for ip in ip_arr:
                    t=threading.Thread(target=self.ssh_cmd,args=(ip,password,command,user,port,options))
                    t.start()

            else:
                for ip in ip_arr:
                    self.ssh_cmd(ip,password,command,user,port,options)

        else: 
            ip=tmp_array[1]
            self.ssh_cmd(ip,password,command,user,port,options)



        # disalbe ssh log
        if options['o'] == True:
            sys.exit(0) 
        # for history
        log_command=self.build_log(sys.argv)
        self.history_upload(log_command) 
        sys.exit(0)

    def ssh_cmd(self, host,password,command, user,port,options):
        if options['w'] != True and options['j'] !=True:
            print "\033[0;36;40m\033[0;32;40m =============== \033[0;33;40m"+host+" \033[0;32;40m===============\033[0m\n"

        if password== 'null':
            cmd = 'ssh -p '+port+" "+host + ' ' + command  
        else:
            shPath = self.root_path+'/lib/tiny_expect.exp'
            command=command.replace('ipaddress',host)
            cmd = shPath + ' ' + '"' + command + '"' + ' ' + port + ' ' + password+ ' ' + user + '@' + host 

        if (options['d'] == True):
            print cmd
            sys.exit(0)

        os.system(cmd)
