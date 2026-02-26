<?php

namespace App\Consulting\Registry;

use App\Consulting\Contract\ConsultingModelInterface;
use App\Entity\User;

class ConsultingModelRegistry
{
    /**
     * @var ConsultingModelInterface[]
     */
    private $models = [];

    /**
     * @param iterable<ConsultingModelInterface> $models
     */
    public function __construct(iterable $models)
    {
        foreach ($models as $model) {
            $key = $model->getKey();

            if (isset($this->models[$key])) {
                throw new \LogicException(sprintf(
                    'Consulting model with key "%s" is already registered.',
                    $key
                ));
            }

            $this->models[$key] = $model;
        }
    }

    public function get(string $key): ConsultingModelInterface
    {
        if (!isset($this->models[$key])) {
            throw new \InvalidArgumentException(sprintf(
                'Consulting model "%s" not found.',
                $key
            ));
        }

        return $this->models[$key];
    }

    public function all(): array
    {
        return $this->models;
    }

    public function supports(string $key, User $user): bool
    {
        if (!isset($this->models[$key])) {
            return false;
        }

        return $this->models[$key]->supports($user);
    }

    public function pickAuto(User $user): ConsultingModelInterface
    {
        // 1) Явный дефолт для auto
        if (isset($this->models['journal_reflection'])) {
            $model = $this->models['journal_reflection'];

            if ($model->supports($user)) {
                return $model;
            }
        }

        // 2) Fallback — первая поддерживающая модель
        foreach ($this->models as $model) {
            if ($model->supports($user)) {
                return $model;
            }
        }

        throw new \RuntimeException('No consulting model supports this user.');
    }
}