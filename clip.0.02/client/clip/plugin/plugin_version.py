#!/usr/bin/env /usr/bin/python
# -*- coding: utf-8 -*-
# @author djangowang@tencent.com 
# @from http://blog.puppeter.com/read.php?7

from plugin_base import plugin_base
import sys

class plugin_version(plugin_base):
    def __init__(self):
        #print "hello demo"
        plugin_base.__init__(self)

    def process(self, options, args):   
        print "Version 0.0.2\nGithub: https://github.com/puppeter/clip"
