<?php

namespace Modules\LMS\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Layout extends Component
{
    protected $data = [];
    protected $class = '';

    /**
     * Create a new component instance.
     */
    public function __construct($data = [], $class = '')
    {
        $this->data = $data;
        $this->class = $class;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $bodyClass = $this->class;
        $activeTheme = function_exists('active_theme_slug') ? active_theme_slug() : null;

        if ($activeTheme) {
            $themeClass = match ($activeTheme) {
                'elearning-education' => 'home-e-learning',
                'lms-education' => 'home-lms-education',
                'digital-education' => 'home-digital-education',
                'kindergarten' => 'home-kindergarten',
                default => null,
            };
            
            if ($themeClass && strpos($bodyClass, $themeClass) === false) {
                $bodyClass .= ' ' . $themeClass;
            }

            if (!isset($this->data['footer']['theme']) || $this->data['footer']['theme'] === 'default') {
                $this->data['footer']['theme'] = $activeTheme;

                if (!isset($this->data['footer']['wrapper_class'])) {
                    $this->data['footer']['wrapper_class'] = match ($activeTheme) {
                        'kindergarten' => 'bg-heading mt-16 sm:mt-24 lg:mt-[120px] relative overflow-hidden',
                        'lms-education' => 'bg-primary mt-16 sm:mt-24 lg:mt-[120px]',
                        'digital-education' => 'bg-gradient-to-t from-[#16413B] to-[#3C5F3F] mt-16 sm:mt-24 lg:mt-[120px]',
                        'elearning-education' => 'bg-[#1B253A] mt-16 sm:mt-24 lg:mt-[120px] bg-[length:100%_100%]',
                        default => 'bg-heading mt-16 sm:mt-24 lg:mt-[120px]',
                    };
                }
            }
        }

        $attibutes = [
            'data' => $this->data,
            'body_class' => trim($bodyClass),
        ];
        return view('theme::layouts.master', $attibutes);
    }
}
