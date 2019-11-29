# coding=utf-8
'''
Created on Apr 23, 2018
Desc: Webp convertor
@author: Mashiro https://2heng.xin
'''
import os
import sys
import json
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
    self.th_jpeg = 'jpeg/' + self.hash + '.th.jpeg'
    self.th_webp = 'webp/' + self.hash + '.th.webp'

  def optimize(self):
    im = Image.open('gallary/' + self.file).convert('RGB')
    im.save(self.jpeg, 'JPEG') # todo: TinyPNG API
    im.save(self.webp, 'WEBP')

  def thumbnail(self):
    im = Image.open('gallary/' + self.file).convert('RGB')
    im.thumbnail((450, 300))
    im.save(self.th_jpeg, 'JPEG')  # todo: TinyPNG API
    im.save(self.th_webp, 'WEBP')

  def manifest(self):
    self.mani[self.hash] = {
      'source': self.file,
      'jpeg': ['jpeg/' + self.hash + '.jpeg', 'jpeg/' + self.hash + '.th.jpeg'],
      'webp': ['webp/' + self.hash + '.webp', 'webp/' + self.hash + '.th.webp']
    }

  def main(self):
    self.hash()
    # if os.path.exists(self.jpeg) and os.path.exists(self.webp):
    self.optimize()
    self.thumbnail()
    self.manifest()
    return self.mani

def main():
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

if __name__ == '__main__':
  main()
  key = input('`manifest.json` saved. Press any key to quit.') 
  quit()