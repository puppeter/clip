#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7
import httplib 
import json
import sys
import md5
import time 
import os 
from urllib import urlencode
import ConfigParser as cparser

class plugin_base:
    def __init__(self):
	root_pwd=os.path.dirname(__file__) 
	root_pwd=root_pwd.replace("plugin","conf")
	config = cparser.ConfigParser()
	config.read(root_pwd+"/clip.ini")
	self.operator = config.get("main","operator") 
	self.signature_key = config.get("main","signature_key") 
	self.ip= config.get("main","server_ip") 
	self.host= config.get("main","server_host")

    def process(self, options, args):
        return ''

    #print help message on screen.
    def print_help(self):
        self.subcommand.parser.print_help()
        self.subcommand.print_example()

    def checkout(self,json_format):
        json_arr=json.dumps(json_format)
        print json_arr
    
    def output_limit(self,ip_arr,limit,order=False):
        arr=limit.split(",")
        start=int(arr[0])
        stop=int(arr[1])
        if (len(ip_arr)<= stop): 
            stop=len(ip_arr)
            
        res=range(start,stop) 
        output=[]
        for r in res:
            output.append(ip_arr[r])
        return output 
    
    def output_format(self,data,options):
        if options['j'] == "|":
            return "|".join(data)
        elif options['j']  == ",":
            return ",".join(data)
        elif options['j']  == "space":
            return " ".join(data)
        else:
            return "\n".join(data)

        return p.findall(str(ret))
        
    def check_ip_cstring(self,input,data):
        if input == "ip":
            ipaddr=data.strip().split('.') 
            if len(ipaddr) != 4:
                self.print_help()
                sys.exit(1) 
            else:
                return True
        elif input == "cstring":
            cstring=data.strip().split('-') 
            if len(cstring) != 4:
                self.print_help()
                sys.exit(1) 
            else:
                return True
        else:
            self.print_help()
            sys.exit(1) 
    
    def checkparam(self,command, options, args):
        if command == "lt":
            if (options['p'] == None): 
                self.print_help()
                sys.exit(1) 
            elif (len(args) < 2): 
                self.print_help()
                sys.exit(1) 

        if command == "ssh":
            if (options['p'] == None): 
                self.print_help()
                sys.exit(1) 
            elif (len(args) < 2): 
                self.print_help()
                sys.exit(1) 
            
        if command == "scp":
            if (options['p'] == None): 
                self.print_help()
                sys.exit(1) 
            fileName = args[0]
            if os.path.exists(fileName) != True:
                print "filename not exists !"
                sys.exit(1)
        
        if command == "cstring":
            if (options['q'] == None) and (options['i'] == None): 
                self.print_help()
                sys.exit(1) 
        
        if command == "property":
            if (options['q'] == None) : 
                self.print_help()
                sys.exit(1) 
        
        if command == "scan":
            if (options['q'] == None) and (options['i'] == None): 
                self.print_help()
                sys.exit(1) 
        
        if command == "tree":
            if (options['q'] == None): 
                self.print_help()
                sys.exit(1) 
        if command == "import":
            if (options['i'] == None): 
                self.print_help()
                sys.exit(1) 

        return True 

    def append_data(self,options,data,plugin_base):
        if (options['q'] != None):
            ip_array=options['a'].split(",")
            if (len(ip_array) >= 2):
                for i in ip_array: 
                    if(len(i.split(".")) == 4 ):
                        data.append(i)
            else:
                if(len(options['a'].split(".")) == 4):
                    data.append(options['a'])
        return data 

    def get_ip_data(self,options,args,plugin):
        res_array={}
        if(options['q'] != None):
            ip_array=options['q'].split(",")
            if (len(ip_array) >= 2):
                str_cstring=",".join(ip_array)
                parameter="parameter=mgetcstring&cstring="+str_cstring
                signature=str_cstring
            else:
                parameter="parameter=getcstring&cstring="+options['q']
                signature=options['q']
            url=self.build_url(options,args,parameter,signature)
            if(options['d'] == True):
                print url

            json_res= self.curl_get_contents(url, None, self.host)
            map_array=json.loads(json_res)
            if map_array['ret'] == '0':
                return map_array['data'].split("|")
            else: 
                print map_array['data']
        else:
            ip_array=options['i'].split(",")
            if(len(ip_array)>= 2):
                parameter="parameter=mgetip&ip="+options['i']
            else:
                parameter="parameter=getip&ip="+options['i']
            signature=options['i']
            url=self.build_url(options,args,parameter,signature)
            if(options['d'] == True):
                print url
            json_res= self.curl_get_contents(url, None,self.host)
            map_array=json.loads(json_res)
            if map_array['ret'] == '0':
                return map_array['data'].split("|")
            else: 
                print map_array['data']

    def build_url(self,options,args,parameter,signature):
        signature = self.build_signature(signature) 
        url = self.ip+"/index.php/Clip/api_version1?"+ parameter +"&operator="+self.operator+"&signature="+str(signature.hexdigest())+"" 
        return url 
    
    
    def build_tree_url(self,cstring):
        signature = self.build_signature(cstring) 
        url = self.ip+"/index.php/Clip/api_get_tree?cstring="+cstring+"&operator="+self.operator+"&signature="+str(signature.hexdigest())+"" 
        return url 
    
    def build_property_url(self,cstring):
        signature = self.build_signature(cstring) 
        url = self.ip+"/index.php/Clip/get_property?cstring="+cstring+"&operator="+self.operator+"&signature="+str(signature.hexdigest())+"" 
        return url 
    
    def build_clip_register(self,parameter):
        signature = self.build_signature(parameter) 
        url = self.ip+"/index.php/Clip/clip_register?"+parameter+"&operator="+self.operator+"&signature="+str(signature.hexdigest())+"" 
        return url 
    
    def history_upload(self,log_command):
        arr={"log":log_command}
        signature = self.build_signature("signature") 
        url = self.ip+"/index.php/Clip/net_log_db?"+urlencode(arr)+"&operator="+self.operator+"&signature="+str(signature.hexdigest())+"" 
        self.curl_get_contents(url,None,self.host)
    
    def get_history(self):
        signature = self.build_signature("signature") 
        url = self.ip+"/index.php/Clip/net_log_db?action=true&log=signature&operator="+self.operator+"&signature="+str(signature.hexdigest())+"" 
        res=self.curl_get_contents(url,None,self.host)
        return res
        
    def build_signature(self,signature):
        hour=time.strftime("%H")
        signature = md5.md5(signature+"-"+self.signature_key+"-"+hour)
        return signature

    def build_log(self,log):    
        return " ".join(log)
            
    def format_output(self,ret):
        return "\n".join(ret.split("|")[:-1])
        
    def curl_get_contents(self, url, post_param = None, host = None):
        response = None
        try:
            headers = {"Content-type": "application/x-www-form-urlencoded"
                                , "Accept": "text/plain"}
            if host != None:
                headers["Host"] = host

            urls = url.split("/", 1)
            conn = httplib.HTTPConnection(urls[0])
            if post_param is not None:
                conn.request("POST", "/" + urls[1], json.dumps(post_param), headers)
            else:
                conn.request("GET", "/" + urls[1], "", headers)

            response = conn.getresponse().read()
            conn.close()
        except Exception, e:
            sys.stderr.write(str(e))
        return response

    def set_root_path(self, root_path):
        self.root_path = root_path

    def set_subcommand(self, subcommand):
        self.subcommand = subcommand

    def set_signature_key(self, signature_key):
        self.signature_key = signature_key

    def set_operator(self, operator):
        self.operator = operator
