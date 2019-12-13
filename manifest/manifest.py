# coding=utf-8
'''
Created on Apr 23, 2018
Desc: Webp convertor
@author: Mashiro https://2heng.xin
'''
import os
import sys
import json
import requests
import base64
import hashlib
from PIL import Image

class Single(object):
  def __init__(self, file, manifest):
    self.file = file
    self.mani = manifest

  def hash(self):
    hasher = hashlib.md5()
    with open('gallary/' + self.file, 'rb') as afile:
      buf = afile.read()
      hasher.update(buf)
    self.hash = hasher.hexdigest()
    self.jpeg = 'jpeg/' + self.hash + '.jpeg'
    self.webp = 'webp/' + self.hash + '.webp'
    self.jpeg_th = 'jpeg/' + self.hash + '.th.jpeg'
    self.webp_th = 'webp/' + self.hash + '.th.webp'

  def optimize(self):
    im = Image.open('gallary/' + self.file).convert('RGB')
    im.save(self.jpeg, 'JPEG') # todo: TinyPNG API
    im.save(self.webp, 'WEBP')
    im.thumbnail((450, 300))
    im.save(self.jpeg_th, 'JPEG')  # todo: TinyPNG API
    im.save(self.webp_th, 'WEBP')

  def manifest(self):
    self.mani[self.hash] = {
      'source': self.file,
      'jpeg': [self.jpeg, self.jpeg_th],
      'webp': [self.webp, self.webp_th]
    }

  def main(self):
    self.hash()
    # if os.path.exists(self.jpeg) and os.path.exists(self.webp):
    self.optimize()
    self.manifest()
    return self.mani


class Upload2Wordpress(object):
  def __init__(self, username, password, url):
    self.username = username
    self.password = password
    self.url = url

  def upload(self, file, field):
    data_string = self.username + ':' + self.password
    token = base64.b64encode(data_string.encode()).decode('utf-8')
    headers = {
      'Authorization': 'Basic ' + token,
      "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97"
    }
    files = {field: open(file, mode="rb")}
    reply = requests.post(self.url, headers=headers, files=files)
    print(json.loads(reply.content)['message'])

  def main(self):
    print('start uploading `manifest.json`...')
    self.upload('manifest.json', 'manifest')


def gen_manifest_json():
  onlyfiles = [f for f in os.listdir('gallary') if os.path.isfile(os.path.join('gallary', f))]
  id = 1
  Manifest = {}
  for f in onlyfiles:
    worker = Single(f, Manifest)
    Manifest = worker.main()
    print(str(id) + '/' + str(len(onlyfiles)))
    id += 1
  with open('manifest.json', 'w+') as json_file:
    json.dump(Manifest, json_file)


def main():
  gen_manifest_json()
  username = input('Enter your username: ')
  password = input('Enter your password: ')
  url = input('Enter your rest api url: ')
  upload = Upload2Wordpress(username, password, url)
  upload.main()


if __name__ == '__main__':
  main()
  key = input('`manifest.json` saved. Press any key to quit.')
  quit()
