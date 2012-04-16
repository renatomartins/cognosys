"""
Lists to-do tags and following description
By default searches all directories recursively from the root of the framework
and list the tags, ordered by file and priority
Alternatively, the script can take files and directories as input to list its tags
Accepted tags (listed by priority): BUG, FIXME, TODO, LOW
"""

__author__ = 'Renato S. Martins'

import os
import re
import sys

todo_tags = ['BUG:', 'FIXME:', 'TODO:', 'LOW:']

def found_todo_msgs(filename, found = {}):
  f = open(filename, 'rU')
  line_number = 0
  for line in f:
    line_number += 1
    match = re.search('\s*('+'.*|'.join(todo_tags)+'.*)', line)
    if match:
      index = f.name
      if not index in found:
        found[index] = []
      found[index].append((line_number, match.group(1)))
  
  f.close()
  
  return found

  
def sortByPriority(msg):
  return (todo_tags.index(msg[1][:msg[1].index(':')+1]), msg[0])


def print_msgs(todos, path):
  print
  for f,msgs in sorted(todos.items()):
    print ' * ' + f[len(path)+1:] + ':'
    for line, text in sorted(msgs, key=sortByPriority):
      print '   ' + str(line) + ':\t' + text
    print


def walk_path(path, found = {}):
  to_exclude = ['Lib', 'public', 'logs', 'scripts']
  this_file = os.path.abspath(__file__)
  
  for root, dirs, files in os.walk(path):
    for ex in to_exclude:
      if ex in dirs:
        dirs.remove(ex)
    
    for filename in files:
      filename = os.path.join(root, filename)
      if filename != this_file:
        found = found_todo_msgs(filename, found)
    
  return found


def main():
  default_path = os.path.abspath(os.path.dirname(os.path.abspath(__file__)) + os.sep + os.pardir)
  found = {}
  
  args = sys.argv[1:]
  cwd = os.getcwd()
  
  for arg in args:
    f = cwd + os.sep + arg
    if os.path.isdir(f):
      found = walk_path(f, found)
    elif os.path.isfile(f):
      found = found_todo_msgs(f, found)
  
  if not args:
    found = walk_path(default_path, found)
  
  print_msgs(found, default_path)
  
#  raw_input()


if __name__ == '__main__':
  main()