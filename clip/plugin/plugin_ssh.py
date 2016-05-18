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
            port="22"
        else:
            port=options['P']

        if len(ipaddress) != 4: 
            options['q']=tmp_array[1].strip().split('.')[0]
            ret=self.get_ip_data(options,args,"cstring")
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
                        self.ssh_cmd(ip,password,command,user,port,options)
            else:
                for ip in ret:
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

    def ssh_cmd(self, host,passwd,command, user,port,options):
        #tiny_expect.exp "uptime" 22 temp@@pwd  root@10.149.29.175
        print "\033[0;36;40m\033[0;32;40m =============== \033[0;33;40m"+host+" \033[0;32;40m===============\033[0m\n"
        shPath = self.root_path+'/lib/tiny_expect.exp'
        command=command.replace('ipaddress',host)
        cmd = shPath + ' ' + '"' + command + '"' + ' ' + port + ' ' + passwd + ' ' + user + '@' + host 
        if (options['d'] == True):
            print cmd
        os.system(cmd)
