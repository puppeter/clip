#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tentcent.com 
# @from http://blog.puppeter.com/read.php?7

import socket
import sys

from plugin_base import plugin_base

class plugin_scan(plugin_base):
    def __init__(self):
        #print "hello demo"
        plugin_base.__init__(self)

    def process(self, options, args):

        self.checkparam("scan",options,args)

        if (options['P'] == None):
            port="80"
        else: 
            port=int(options['P'])

        if (options['q'] != None):
            ret=self.get_ip_data(options,args,"scan")
        else:
            ip = options['i']
            self.scan(ip,port)
            sys.exit(0)
        
        # for -append 
        if (options['a'] != None):
            ret=self.append_data(options,ret,"scan")

        # for limit
        if(options['l'] != None):
            ret=self.output_limit(ret,options['l'])

        # remove ip
        if (options['r'] != None): 
            for ip in ret:
                if ip in options['r']:
                    continue
                else:
                    self.scan(ip,port)
        else:
            for ip in ret:
                self.scan(ip,port)

        # disable sacn log
        if options['o'] == True:
            sys.exit(0) 

        # for history
        log_command=self.build_log(sys.argv)
        self.history_upload(log_command) 
        sys.exit(0)
    def scan(self, ip,port,timeout=3):
        fd = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        fd.settimeout(timeout)
        try:
            fd.connect((ip,port))
            print('\033[0;36;40m\033[0;32;40m Server %s port %s OK!\033' % (ip,port))
        except Exception:
            print('\033[0;36;40m\031[0;32;40m Server  %s port %s is not connected!\033' % (ip,port))
        fd.close()
