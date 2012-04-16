"""
Utility functions to the cognosys framework
"""
__author__ = 'Renato S. Martins'

import os
from re import sub

path = os.path.abspath(os.path.dirname(os.path.abspath(__file__)) + os.sep + os.pardir) + os.sep
app_path = path + 'App' + os.sep
modules_path = path + 'App' + os.sep + 'Modules' + os.sep


#controllers_path = app_path + 'Controllers' + os.sep
#views_path = app_path + 'Views' + os.sep
#models_path = app_path + 'Models' + os.sep + 'Entities' + os.sep
#repositories_path = app_path + 'Models' + os.sep + 'Repositories' + os.sep

def controllers_path(module_name):
  controllers_path = (modules_path + '%s' + os.sep + 'Controllers' + os.sep) % module_name
  if not os.path.isdir(controllers_path):
    os.makedirs(controllers_path)
  return controllers_path

def models_path(module_name):
  models_path = (modules_path + '%s' + os.sep + 'Models' + os.sep + 'Entities' + os.sep) % module_name
  if not os.path.isdir(models_path):
    os.makedirs(models_path)
  return models_path

def repositories_path(module_name):
  repositories_path = (modules_path + '%s' + os.sep + 'Models' + os.sep + 'Repositories' + os.sep) % module_name
  if not os.path.isdir(repositories_path):
    os.makedirs(repositories_path)
  return repositories_path

def views_path(module_name):
  views_path = (modules_path + '%s' + os.sep + 'Views' + os.sep) % module_name
  if not os.path.isdir(views_path):
    os.makedirs(views_path)
  return views_path


def camelCase(str):
  str = str.strip('-')
  str = sub(r'-(\w)', lambda m: m.group(1).upper(), str)
  return str[0].upper() + str[1:]
