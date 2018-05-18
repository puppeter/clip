#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import sys
import os
import json

class plugin_import(plugin_base):
    def __init__(self):
        plugin_base.__init__(self)

    def process(self, options, args):   
        if options['l'] == True:
            self.clip_list()
        elif options['b'] == True:
            self.build_template("clip_template")
        elif options['i'] != None:
            self.clip_insert(options)
        elif options['d'] != None:
            self.clip_delete(options)
        else:
           self.print_help()
           sys.exit(1)
        
    def clip_list(self):  
        print """ 
key=>values
+-----+---------+---------+-------+------+-----+--------------+----------+
| idc | product | modules | group | ext  | s_k | s_v          | operator |
+-----+---------+---------+-------+------+-----+--------------+----------+
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.1  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.2  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.3  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.4  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.5  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.6  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.7  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.8  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.9  | wds      |
| bj  | qq      | qzone   | web   | 0    | ip  | 192.168.0.10 | wds      |
| sh  | qq      | qzone   | web   | 0    | ip  | 192.168.0.11 | wds      |
| sh  | qq      | qzone   | web   | 0    | ip  | 192.168.0.12 | wds      |
| sh  | qq      | qzone   | web   | 0    | ip  | 192.168.0.13 | wds      |
+-----+---------+---------+-------+------+-----+--------------+----------+

values=>key
+-----+---------+-----------------+------------+------+----------------+------+---------------------+
| idc | product | modules         | group      | ext  | ipaddress      | flag | timestamp           |
+-----+---------+-----------------+------------+------+----------------+------+---------------------+
| bj  | qq      | qzone           | web        | 0    | 192.168.0.1    |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | cgi             | production | 0    | 10.237.128.185 |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | cgi             | production | 0    | 10.237.132.148 |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | api             | production | 0    | 10.247.78.101  |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | api             | production | 0    | 10.247.76.106  |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | cloudgateway    | production | 0    | 10.247.32.126  |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | cloudgateway    | production | 0    | 10.247.86.140  |    1 | 2017-10-19 15:16:54 |
| sh  | csg     | cloudgateway    | production | 0    | 10.247.78.105  |    1 | 2017-10-19 15:16:54 |
+-----+---------+---------+-------+---------------------------------+-----+--------------+----------+
"""

    def build_template(self,filename):
        file="""example|                   Format
example|idc|product|modules|group|port|key|values|operator
example|bj|qq|qzone|web|0|ip|192.168.0.1|wds\n
"""
        f = open ( filename, 'w' ) 
        f.write(file) 
        f.close
        print "Create Template Succ"

    def clip_insert(self,options):

        if os.path.exists(options['i']) != True:
            print "filename:"+options['i']+" not exists"
            sys.exit(1)

        file_object = open(options['i'])
        while True:
            line = file_object.readline()
            if line:
                pass
                line=line.strip()
                line_arr=line.split("|")
                if line_arr[0] != 'example' and len(line_arr) == 8:
                    parameter="idc="+line_arr[0]+"&product="+line_arr[1]+"&modules="+line_arr[2]+"&group="+line_arr[3]+"&port="+line_arr[4]+'&v='+line_arr[6]+'&owner='+line_arr[7]
                    url=self.build_clip_register(parameter)
                    json_res=self.curl_get_contents(url,None,self.host)
                    map_array=json.loads(json_res)
                    if map_array['ret'] == '0':
                        print map_array['data']
                    else: 
                        print map_array['data']
                else:
                    print "format error"
            else:
                break
        file_object.close()
    

    def clip_delete(self,options):

        if os.path.exists(options['d']) != True:
            print "filename:"+options['d']+" not exists"
            sys.exit(1)

        file_object = open(options['d'])
        while True:
            line = file_object.readline()
            if line:
                pass
                ip=line.strip() 
                
                if self.check_is_ip(ip):
                    url="ip="+ip+"&owner=guest"
                    signature=self.build_signature(url)
                    parameter="ip="+ip+"&owner=guest&signature="+str(signature.hexdigest())
                    url=self.build_clip_delete(parameter)
                    json_res=self.curl_get_contents(url,None,self.host)
                    map_array=json.loads(json_res)
                    if map_array['ret'] == '0':
                        print map_array['data']
                    else: 
                        print map_array['data']
                else:
                    print "format error"
            else:
                break
        file_object.close()
