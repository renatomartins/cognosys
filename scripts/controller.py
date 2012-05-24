'''
Creates controller and files for the views
This script receives at least two argument, the module name and the controller name
followed by an optional list of view names (all with dashes)

$ controller.py ModuleName controller-name-with-dashes [first-view-name second-view-name ...]
'''

__author__ = 'Renato S. Martins'

import sys
import os
import cognosys

controller_skelleton = '''<?php
namespace App\Modules\%s\Controllers;
use Cognosys\Controller;

class %s extends Controller
{%s}
'''

action_skelleton = '''
	public function %s()
	{
		// action content
	}
'''


def createViews(views_path, views):
  for view_name in views:
    f = open(views_path + view_name + '.php', 'w')
    print ' created view:       ' + os.path.basename(f.name)
    f.close()


#TODO: only add actions if controller exists
def createController(module_name, controller_name, views):
  views_path = cognosys.views_path(module_name) + controller_name + os.sep
  if not os.path.isdir(views_path):
    os.makedirs(views_path)
  
  controller_name = cognosys.camelCase(controller_name) + 'Controller'
  # create view 'index' by default
  if not 'index' in views:
    views.insert(0, 'index')
  controller_actions = getActions(views)
  f = open(cognosys.controllers_path(module_name) + controller_name + '.php', 'w')
  f.write(controller_skelleton % (module_name, controller_name, controller_actions))
  print ' created controller: ' + os.path.basename(f.name)
  f.close()
  
  createViews(views_path, views)


def getActions(views):
  output = ''
  for view in views:
    action_name = cognosys.camelCase(view)
    action_name = action_name[0].lower() + action_name[1:] + 'Action'
    output += action_skelleton % action_name
  return output


def main():
  args = sys.argv[1:]
  
  if len(args) == 0:
    module = raw_input('Module: ')
    controller = raw_input('Controller: ')
    views = raw_input('Views: ').split(' ')
  elif len(args) < 2:
    print 'usage: %s ModuleName controller-name-with-dashes [first-view-name second-view-name ...]' % os.path.basename(__file__)
    sys.exit()
  else:
    (module, controller, views) = args[0], args[1], args[2:]
  
  createController(module, controller, views)


if __name__ == '__main__':
  main()
