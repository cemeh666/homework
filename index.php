<?php

require('data.php');

class Context
{
    private $course;

    public function __construct(Courses $course)
    {
        $this->course = $course;
    }

    public function setCourse(Courses $course)
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

//если бы было больше времени:
//для удобства можно было обернуть инициализацию в класс с доступными курсами валют
//обработка исключений, если источник недоступен или кэш или база

$context = new Context(new CourseUSD());

echo "\nResult Course USD: ".$context->getActualCourse()."\n\n";

$context->setCourse(new CourseEUR());

echo "\nResult Course EUR: ".$context->getActualCourse()."\n\n";

