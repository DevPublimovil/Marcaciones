<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use  \Carbon\Carbon as Fecha;
use Illuminate\Support\Facades\Storage;

class ActionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'created_at' => Fecha::parse($this->date_action)->format('d/m/Y'),
            'check_gte' => $this->check_gte,
            'check_rh' => $this->check_rh,
            'check_employee' => $this->check_employee,
            'employee_id' => $this->employee_id,
            'comment' => $this->comments,
            'attached' => ($this->attached) ? Storage::disk('public')->url($this->attached) : null,
            'diffHumans' => Fecha::parse($this->created_at)->diffForHumans(),
            'name_employee' => $this->user->name ?? $this->name ?? $this->name_employee . ' ' . $this->surname_employee,
        ];
    }
}
