#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import sys
import json

class plugin_tree(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)

    def process(self, options, args):   
        self.checkparam("tree",options,args)
        
        options_arr ={}
        if options['q'] != None:
                parameter="cstring="+options['q']
                signature=options['q']
                url= self.build_tree_url(options['q'])
		if options['d'] == True:
			print url

                json_res= self.curl_get_contents(url, None, self.host)
                map_array=json.loads(json_res)
                if(map_array['ret'] == '0'):
                    data=map_array['data'].split("|")
                    if options['j'] != None:
                        print self.output_format(data,options)
                    else: 
                        print options['q']
                        for i in map_array['data'].split("|"):
                            str=i.split("-")
                            print "|_"+i 
                else: 
                    print map_array['data']; 

                    
        # disable cstring log    
        if options['o'] == True:
            sys.exit(0) 

        log_command="clip tree -q "+options['q'] 
        self.history_upload(log_command) 
        sys.exit(0) 
