'''
Creates model and repository files
This script receives two arguments, the model name (camel-cased) and the table name (underscored)
'''

__author__ = 'Renato S. Martins'

import sys
import os
import cognosys

model_skelleton = '''<?php
namespace App\Modules\%s\Models\Entities;
use Core\Model;

/**
 * @Entity(repositoryClass="App\Modules\%s\Models\Repositories\%s")
 * @Table(name="%s")
 */
class %s extends Model
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 */
	protected $id;
	
	// other fields
	
	public function validate()
	{
		// use Core\Validators to validate model properties
	}
}
'''

repository_skelleton = '''<?php
namespace App\Modules\%s\Models\Repositories;
use Doctrine\ORM\EntityRepository;

class %s extends EntityRepository
{
	
	// repository functions
	
}
'''


def createRepository(module_name, repository_name):
  f = open(cognosys.repositories_path(module_name) + repository_name + '.php', 'w')
  f.write(repository_skelleton % (module_name, repository_name))
  print ' created model:      ' + os.path.basename(f.name)
  f.close()


def createModel(module_name, model_name, table_name):
  f = open(cognosys.models_path(module_name) + model_name + '.php', 'w')
  f.write(model_skelleton % (module_name, module_name, model_name, table_name, model_name))
  print ' created repository: ' + os.path.basename(f.name)
  f.close()
  
  createRepository(module_name, model_name)


def main():
  args = sys.argv[1:]
  
  if len(args) == 0:
    module = raw_input('Module: ')
    model = raw_input('Model: ')
    table = raw_input('Table: ')
  elif len(args) < 3:
    print 'usage: %s ModuleName ModelNameCamelCased table_name_underscored' % os.path.basename(__file__)
    sys.exit()
  else:
    (module, model, table) = args[0], args[1], args[2]
  
  createModel(module, model, table)


if __name__ == '__main__':
  main()
