<?php

namespace App\Transformers;

use App\Seller;
use League\Fractal\TransformerAbstract;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identificador' => (int) $seller->id,
            'nombre' => (string) $seller->name,
            'correo' => (string) $seller->email,
            'esVerificado' => (int) $seller->verified,
            'fechaCreacion' => (string) $seller->created_at,
            'fechaActualizacion' => (string) $seller->updated_at,
            'fechaEliminación' => isset($seller->deleted_at) ? (string) $seller->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'titulo' => 'name',
            'correo' => 'email',
            'esVerificado' => 'verified',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminación' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
