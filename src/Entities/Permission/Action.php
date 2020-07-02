<?php namespace CI4Xpander_Dashboard\Entities\Permission;

class Action extends \CI4Xpander_Dashboard\Entities\Permission
{
    protected $attributes = [
        'C' => false,
        'R' => false,
        'U' => false,
        'D' => false
    ];

    public function __construct(?array $data = null)
    {
        $this->casts = array_merge($this->casts, [
            'C' => 'boolean',
            'R' => 'boolean',
            'U' => 'boolean',
            'D' => 'boolean'
        ]);

        parent::__construct($data);
    }
}