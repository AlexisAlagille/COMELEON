<?php

namespace App\Service;

use App\Repository\PrestationRepository;
use Symfony\Component\HttpFoundation\RequestStack;

final class PanierService
{
    private const SESSION_KEY = 'panier_prestations';

    // La session conserve les identifiants ; le dépôt permet de retrouver les prestations en base.
    public function __construct(
        private RequestStack $requestStack,
        private PrestationRepository $prestationRepository,
    ) {
    }

    private function getIds(): array
    {
        $storedIds = $this->requestStack->getSession()->get(self::SESSION_KEY, []);

        // Une session mal formée ne doit pas empêcher l'affichage du panier.
        if (!is_array($storedIds)) {
            return [];
        }

        $ids = [];
        foreach ($storedIds as $storedId) {
            // La session peut contenir des valeurs inattendues : on ne garde que les entiers positifs.
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

        // Évite une requête à la base quand le panier est vide.
        if ($ids === []) {
            return [];
        }

        // Doctrine ne garantit pas l'ordre des résultats : on les indexe par identifiant.
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

        // Retire du panier les prestations supprimées depuis leur ajout.
        if ($validIds !== $ids) {
            $this->storeIds($validIds);
        }

        return $prestations;
    }

    public function add(int $prestationId): bool
    {
        $ids = $this->getIds();

        // Une prestation ne peut apparaître qu'une seule fois dans le panier.
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
