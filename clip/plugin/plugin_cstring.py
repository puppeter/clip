#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import json
import sys

class plugin_cstring(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)


    def process(self, options, args):
        # checkparam
        self.checkparam("cstring",options,args)

        # get ip data
        ret=self.get_ip_data(options,args,"cstring")

        # format output
        if (options['l'] != None):
            ret=self.output_limit(ret,options['l'])
        
        # for -append 
        if (options['a'] != None):
            ret=self.append_data(options,ret,"cstring")

        # remove ip
        tmp=[]
        i=1
        if (options['r'] != None):
            for ip in ret:
                if ip in options['r']:
                    i=i+1
                    continue
                else:
                    tmp.append(ip)
            ret=tmp 
        # print "\n".join(ret)
        res=self.output_format(ret,options)
        print res 
        
        # count
        if options['c'] == True:
            count=str(len(ret))
            print "count:" + count

        # disable cstring log    
        if options['o'] == True:
            sys.exit(0) 

        # for history
        log_command=self.build_log(sys.argv)
        self.history_upload(log_command) 
        sys.exit(0)
