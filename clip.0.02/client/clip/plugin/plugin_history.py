#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import sys

class plugin_history(plugin_base):
    def __init__(self):
        #print "hello demo"
        plugin_base.__init__(self)

    def process(self, options, args):   
        ret=self.get_history().split("|")[:-1] 
        i=1
        for log in ret:
            print str(i)+"  "+log 
            i=i+1
	sys.exit(0)	
