<?php
declare(strict_types=1);

class TeacherDTO
{
  public int $id;
  public string $fullName;
  public string $position;
  /**
   * @var string[] $phones
   */
  public array $phones;  // Массив телефонов
  public string $mainWorkplace;
}
