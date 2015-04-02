#!/usr/bin/python
# -*- coding: utf-8 -*-

import webapp2
import json
import re
from urllib import unquote
from google.appengine.api import urlfetch

class MainPage(webapp2.RequestHandler):
	def get(self):
		h = self.request.get('h')
		c = self.request.get('c')
		v = r''
		flv = r''
		if h=='xhm':
			v = r'http://xhamster.com/movies/' + c+ r'/0_1_2.html'
		if h=='rtb':
			v = r'http://www.redtube.com/' + c
		result = urlfetch.fetch(v)
		self.response.headers.add_header('Access-Control-Allow-Origin','*')
		self.response.headers.add_header('Access-Control-Allow-Credentials','true')
		if result.status_code == 200:
			if h=="xhm":
				m = re.search(r'<a href="(.+?)" class="mp4Thumb" target="_blank">',result.content)
				f = re.search(r"srv=http%3A%2F%2F(.+?)&file=(.+?)&image=",result.content)
				if not f:
					f=re.search(r"url_mode=3&srv=&file=(.+?)&image=",result.content)
					flv=f.group(1)
				else:
					flv=f.group(1)+'/key='+f.group(2)
				t = re.search(r"<title>(.+?) - xHamster.com</title>",result.content)
			if h=='rtb':
				m =re.search(r'<source src="(.+?)" type="video\/mp4">',result.content)
				f =re.search(r'p=(.+?)&embedCode=%3Ciframe',result.content)
				flv=f.group(1)
				t = re.search(r'<h1 class="videoTitle">(.+?)</h1>',result.content)
			if m:
				j = dict(flv=unquote(flv),mp4=m.group(1),title=t.group(1),code=c,host=h)
				self.response.out.write(json.dumps(j))
			else:
				self.response.out.write("文件已删除")
		else:
			self.response.out.write('主机不在线')

application=webapp2.WSGIApplication([
('/',MainPage)
],debug=False)

def main():
	application.run()

if __name__=="__main__":
	main()
