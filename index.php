<?php

require('data.php');

class Context
{
    private $course;

    public function __construct(Courses $course)
    {
        $this->course = $course;
    }

    public function getActualCourse()
    {
        return $this->course->getCourse();
    }
}


interface Courses
{
    public function getCourse();
    public function cacheKey():   string;
    public function fieldValue(): string;
    public function sourceUrl():  string;
}

class CourseUSD implements Courses
{
    public function cacheKey(): string{
        return 'USD';
    }

    public function fieldValue(): string{
        return 'USD';
    }

    public function sourceUrl(): string{
        return 'http://mfd.ru/currency/?currency=USD';
    }

    public function getCourse()
    {
        $actual_course = new actualCourse(new Data($this));
        return $actual_course->get();
    }
}

class CourseEUR implements Courses
{
    public function cacheKey(): string{
        return 'EUR';
    }

    public function fieldValue(): string{
        return 'EUR';
    }

    public function sourceUrl(): string{
        return 'http://mfd.ru/currency/?currency=EUR';
    }

    public function getCourse()
    {
        $actual_course = new actualCourse(new Data($this));
        return $actual_course->get();
    }
}

class Client
{
    private $available_courses = [
        'USD', 'EUR'
    ];

    public function getCourse(string $course){
        if(!isset($this->available_courses, $course) || !class_exists("Course".$course)){
            return "Not available course: $course, \navailable: ".implode(', ', $this->available_courses);
        }
        $class_name = "Course".$course;
        $context = new Context(new $class_name());

        return $context->getActualCourse();
    }

}

//если бы было больше времени:
//обработка исключений, если источник недоступен или кэш или база
//обработка не поддерживаемых курсов

$client = new Client();

echo "\nResult Course USD: ".$client->getCourse("USD")."\n\n";

echo "\nResult Course EUR: ".$client->getCourse("EUR")."\n\n";

echo "\nResult Course RUB: ".$client->getCourse("RUB")."\n\n";

