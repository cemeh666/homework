<?php


/**
 * Получаем значения курсов из доступных источников
 * Class actualCourse
 */
class actualCourse{

    private $available_drivers = [
        'cache', 'db', 'source'
    ];

    private $drivers;

    public function __construct(Drivers $drivers)
    {
        $this->drivers = $drivers;
    }

    public function get(){

        foreach ($this->available_drivers as $driver){

            if(method_exists($this->drivers, $driver)){
                $result = $this->drivers->$driver();
                if(is_null($result)){
                    continue;
                }
                return $result;
            }

        }
        //если источники не дали результатов возвращаем NULL
        return null;
    }
}

interface Drivers{
    public function cache();
    public function db();
    public function source();
}

class Data implements Drivers
{
    private $course;

    public function __construct(Courses $course)
    {
        $this->course = $course;
    }

    public function cache()
    {
        //вызываем и используем ключ для вывода значения их кэша
        $this->course->cacheKey();
        $result = rand(0, 50);
        echo "Result CACHE {$this->course->cacheKey()}: {$result}\n";
        return $result < 20 ? $result : null;
    }

    public function db()
    {
        //вызываем и используем значение для вывода из базы
        $this->course->fieldValue();
        $result = rand(0, 50);
        echo "Result DB {$this->course->cacheKey()}: {$result}\n";

        $result = $result < 20 ? $result : null;

        //если в базе существует значение кладём в кэш
        if(!is_null($result)){
            $this->setValueCache($result);
        }
        return $result < 20 ? $result : null;
    }

    public function source()
    {
        //вызываем и используем ссылку для получения значения из источника
        $this->course->sourceUrl();
        $result = rand(0, 50);
        echo "Result SOURCE {$this->course->cacheKey()}: {$result}\n";
        $this->setValueCacheAndDB($result);
        return $result;
    }

    //установка значений в кэш и БД
    public function setValueCache($value): void {
        //используем ключ для сохранение в кэш
        $this->course->cacheKey();
    }

    //установка значений в кэш и БД
    public function setValueCacheAndDB($value): void {
        //используем ключ для сохранение в кэш
        $this->course->cacheKey();
        //используем значение для записи в базу
        $this->course->fieldValue();
    }
}