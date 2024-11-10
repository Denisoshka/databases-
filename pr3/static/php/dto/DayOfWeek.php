<?php
declare(strict_types=1);
require_once "GroupDTO.php";
require_once "TeacherDTO.php";
require_once "SubjectDTO.php";
class DayOfWeek
{
  public int $id;
  public string $dayOfWeek;
  public int $classNumber;
  public GroupDTO $group;
  public TeacherDTO $teacher;
  public SubjectDTO $subject;
}
