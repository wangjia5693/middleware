<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/3/2
 * Time: 14:32
 */
namespace Middleware;
class Container{

    /**
     * 已绑定未解析
     * @var array
     */
    private $binds = [];

    /**
     * 已经绑定的对象
     * @var array
     */
    private $instances = [];

    /**
     * 初始化
     * Container constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {

    }


    /**
     * 手动绑定对象到容器
     * @param $className
     * @param $obj
     * @throws
     */
    public function bind($className,$obj){
        if(is_object($obj)){
            $this->instances[$className] = $obj;
        }else if($obj instanceof \Closure || class_exists($className)){
            $this->binds[$className] = $obj;
        }else{
            throw new \Exception('The bind is not unable!');
        }
    }

    /**
     * 是否已经存在
     * @param $id
     * @return bool
     */
    public function has($id)
    {
        return isset($this->instances[$id]);
    }

    public function get($id)
    {
        return $this->instances[$id];
    }
    /**
     * 自动绑定并且解析对象
     * @param array $className
     * @return mixed
     * @throws
     *
     */
    public function build($className){
        if($className instanceof \Closure){
            return $this->instances[$className] = call_user_func($className,$this);
        }
        $reflection = new \ReflectionClass($className);
        if(!$reflection->isInstantiable()){
            throw new \Exception('The class cannot be instanced！');
        }

        if(is_null($reflection->getConstructor())){
            return $this->instances[$className] = new $className();
        }


        //$params = $reflection->getConstructor()->getParameters();
        return $this->instances[$className] = $reflection->newInstanceArgs($this->getDependencies($reflection->getConstructor()));
    }

    /**
     * 递归解析参数
     * @param \ReflectionMethod $rfMethod
     * @return array
     * @throws \Exception
     */
    public function getDependencies(\ReflectionMethod $rfMethod){
        $instanceParams = [];

        foreach($rfMethod->getParameters() as $param){
            $dependency = $param->getClass();

            if(is_null($dependency)){   //该参数不是对象
                if($param->isDefaultValueAvailable()){
                    $instanceParams[] = $param->getDefaultValue();
                }else{
                    throw new \Exception('The class has a container not support params!');
                }
            }elseif($dependency->name=='Middleware\Container'){
                $instanceParams[] = $this;
            }
            else{
                $instanceParams[] = $this->make($dependency->getName());
            }
        }
        return $instanceParams;
    }

    /**
     * 从容器中解析对象
     * @param $key
     * @return object
     * @throws \Exception
     */
    public function make($key){

        if(isset($this->instances[$key])){
            return $this->instances[$key];
        }
        if(in_array($key,$this->binds)){
            $key = $this->binds[$key];
            unset($this->binds[$key]);
            return $this->build($key);
        }
        throw new \Exception('class '.$key.' not in container!');
    }
}
