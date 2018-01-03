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
        try:
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

            output=[]
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
                        if options['j'] == True:
                            output.append(ip)
                            output.append(self.ssh_cmd(ip,password,command,user,port,options))
                        else:
                            self.ssh_cmd(ip,password,command,user,port,options)

            else: 
                ip=tmp_array[1]
                if options['j'] == True:
                    output.append(ip)
                    output.append(self.ssh_cmd(ip,password,command,user,port,options))
                else:
                    self.ssh_cmd(ip,password,command,user,port,options)


            if options['j'] == True:
                import json
                outputs={}
                outputs['retcode']=0
                outputs['data']=output
                print json.dumps(outputs)

            # disalbe ssh log
            if options['o'] == True:
                sys.exit(0) 
            # for history
            log_command=self.build_log(sys.argv)
            self.history_upload(log_command) 
            sys.exit(0)
        except Exception:
            print "unknow error"
            sys.exit(1)

