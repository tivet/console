<?php

    namespace Tivet\Console;

    use Colors\Color;

    class Console
    {
        /**
         * @var Color
         */
        protected static $color;


        public static function init()
        {
            if (!static::$color) {
                static::$color = new Color();
            }
        }


        public static function write(string $string, int $br = 0, int $brTop = 0)
        {
            $string .= str_repeat(PHP_EOL, $br);
            $string = str_repeat(PHP_EOL, $brTop) . $string;

            echo (string) $string;
        }


        public static function list(array $list, int $indent = 0)
        {
            $maxLength = max(array_map(function ($string) {
                $string = Console::color()->clean($string);

                return mb_strlen($string);
            }, array_keys($list)));

            $indent = str_repeat(' ', $indent);

            foreach ($list as $left => $right) {
                $length = mb_strlen(Console::color()->clean($left));

                if ($length < $maxLength) {
                    $left .= str_repeat(' ', ($maxLength - $length));
                }

                self::write("{$indent}{$left}  :  ");
                self::write($right, 1);
            }
        }


        public static function writeError(string $string, int $br = 0, int $brTop = 0)
        {
            self::write(self::color("[" . lang('app.words.error') . "]")->red . " ", 0, $brTop);
            self::write($string, $br);
        }


        public static function writeInfo(string $string, int $br = 0, int $brTop = 0)
        {
            self::write(self::color("[" . lang('app.words.info') . "]")->light_blue . " ", 0, $brTop);
            self::write($string, $br);
        }


        public static function writeEvent(string $string, int $br = 0, int $brTop = 0)
        {
            self::write(self::color("[" . lang('app.words.event') . "]")->yellow . " ", 0, $brTop);
            self::write($string, $br);
        }


        /**
         * @param string $string
         * @return Color
         */
        public static function color(string $string = '')
        {
            if (!static::$color) {
                static::$color = new Color();
            }

            return (static::$color)($string);
        }


        public static function getWindowWidth()
        {
            if (getenv('ROWS')) {
                return getenv('ROWS');
            }

            if (is_int((int) exec('tput cols'))) {
                return exec('tput cols');
            }

            return false;
        }


        public static function getWindowHeight()
        {
            if (getenv('COLUMNS')) {
                return getenv('COLUMNS');
            }

            if (is_int((int) exec('tput lines'))) {
                return exec('tput lines');
            }

            return false;
        }
    }