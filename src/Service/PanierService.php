<?php

namespace App\Service;

use App\Entity\Prestation;
use App\Repository\PrestationRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class PanierService
{
    private const SESSION_KEY = 'panier_prestations';

    public function __construct(
        private RequestStack $requestStack,
        private PrestationRepository $prestationRepository,
    ) {
    }

    private function getIds(): array
    {
        $storedIds = $this->requestStack->getSession()->get(self::SESSION_KEY, []);
        if (!is_array($storedIds)) {
            return [];
        }

        $ids = [];
        foreach ($storedIds as $storedId) {
            $id = filter_var($storedId, FILTER_VALIDATE_INT);
            if ($id !== false && $id > 0) {
                $ids[] = $id;
            }
        }

        return array_values(array_unique($ids));
    }

    public function getPrestations(): array
    {
        $ids = $this->getIds();
        if ($ids === []) {
            return [];
        }

        $prestationsById = [];
        foreach ($this->prestationRepository->findBy(['id' => $ids]) as $prestation) {
            $prestationsById[$prestation->getId()] = $prestation;
        }

        $prestations = [];
        $validIds = [];
        foreach ($ids as $id) {
            if (isset($prestationsById[$id])) {
                $validIds[] = $id;
                $prestations[] = $prestationsById[$id];
            }
        }

        if ($validIds !== $ids) {
            $this->storeIds($validIds);
        }

        return $prestations;
    }

    public function add(int $prestationId): bool
    {
        $ids = $this->getIds();
        if (in_array($prestationId, $ids, true)) {
            return false;
        }

        $ids[] = $prestationId;
        $this->storeIds($ids);

        return true;
    }

    public function remove(int $prestationId): void
    {
        $ids = array_values(array_filter(
            $this->getIds(),
            static fn (int $id): bool => $id !== $prestationId,
        ));
        $this->storeIds($ids);
    }

    public function clear(): void
    {
        $this->storeIds([]);
    }

    private function storeIds(array $ids): void
    {
        $this->requestStack->getSession()->set(self::SESSION_KEY, $ids);
    }
}
