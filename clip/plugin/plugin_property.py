#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import json
import sys

class plugin_property(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)


    def process(self, options, args):

        self.checkparam("property",options,args)

        if options['q'] != None:
            url=self.build_property_url(options['q'])
        if options['d'] == True:
            print url

        property = self.curl_get_contents(url,None,self.host)

        map_array=json.loads(property)
        if map_array['ret'] == '0':
            if map_array['data']['cstring'] != None:
                for k,v in map_array['data'].items():
                    print k+": "+v 
            else:
                print "data empty"
        else: 
            print map_array['data']

        # disable cstring log    
        if options['o'] == True:
            sys.exit(0) 

        # for history
        log_command=self.build_log(sys.argv)
        self.history_upload(log_command) 
        sys.exit(0)

