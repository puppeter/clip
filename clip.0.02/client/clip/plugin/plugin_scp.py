#!/usr/bin/env /usr/bin/python 
# -*- coding: utf-8 -*-
# @author djangwoang@tencent.com 
# @from http://blog.puppeter.com/read.php?7 

from plugin_base import plugin_base
import os
import sys

class plugin_scp(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)

    def process(self, options, args):
        try:
            self.checkparam("scp",options,args)

            filename = args[0]
            password = options['p']
            tmpstr = args[1]
            tmpary = tmpstr.split('@')
            username = tmpary[0]
            tary = tmpary[1].split(':')
            options['q']= tary[0]
            path = tary[1]
            ipaddr=options['q'].strip().split('.')


            #dim
            if (options['P'] == None):
                port=self.ssh_port
            else:
                port=options['P']

            if len(ipaddr) != 4: 
                # get ip data
                ret=self.get_ip_data(options,args,"scp")

                
                ip_arr=[]
                output=[]
                if(options['l'] != None):
                    ret=self.output_limit(ret,options['l'])

                if(options['r'] != None):
                    for ip in ret:
                        if ip in options['r']:
                            continue
                        else: 
                            ip_arr.append(ip)
                else:
                    ip_arr=ret

                if options['w'] ==True:
                    import threading
                    for ip in ip_arr:
                        t=threading.Thread(target=self.scp_cmd,args=(filename,password,username,ip,path,port,options))
                        t.start()
                else:
                    for ip in ip_arr:
                        self.scp_cmd(filename,password,username,ip,path,port,options)
                        if (options['s'] != None) and options['s'].isdigit():
                            import time
                            time.sleep(int(options['s']))

            else:
                self.scp_cmd(filename,password,username,options['q'],path,port,options)
            

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
