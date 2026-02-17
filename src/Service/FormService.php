<?php

namespace App\Service;

class FormService
{

    public function validar($dadesForm): array
    {
        $errors = [];

        if (empty($dadesForm['codi'])) {
            $errors['codi'] = "El codi és obligatori";
        }

        if (!isset($dadesForm['nom']) || mb_strlen(trim($dadesForm['nom'])) < 3) {
            if (empty($dadesForm['nom'])) {
                $errors['nom'] = "El nom és obligatori";
            } else {
                $errors['nom'] = "El nom ha de tenir almenys 3 caràcters";
            }
        }


        if (empty($dadesForm['data_inici'])) {
            $errors['data_inici'] = "La data d'inici és obligatoria";
        } else {
            $dataInici = $dadesForm['data_inici'];
            $hoy = new \DateTime();
            $hoy->setTime(0,0,0);

            if (!$dataInici) {
                $errors['data_inici'] = "Format de data invàlid";
            } elseif ($dataInici < $hoy) {
                $errors['data_inici'] = "La data d'inici ha de ser major o igual a l'actual";
            }
        }

        if (empty($dadesForm['data_fi'])) {
            $errors['data_fi'] = "La data de finalització és obligatoria";
        } else {
            $dataFi = $dadesForm['data_fi'];

            if (!$dataFi) {
                $errors['data_fi'] = "Format de data invàlid";
            } elseif (!empty($dataInici) && $dataFi < $dataInici) {
                $errors['data_fi'] = "La data fi ha de ser major o igual a la data d'inici";
            }
        }

        if (!isset($dadesForm['duracio']) || trim($dadesForm['duracio']) === '') {
            $errors['duracio'] = "La duració és obligatoria";
        } else {
            $duracio = str_replace(',', '.', $dadesForm['duracio']);
            $decimal = $duracio - floor($duracio);
            
            if (!is_numeric($duracio)) {
                $errors['duracio'] = "La duració ha de ser un número";
            } elseif ($decimal != 0 && $decimal != 0.5) {
                $errors['duracio'] = "La duració ha de ser múltiple de 0.5";
            }
        }

        if (!isset($dadesForm['preu']) || trim($dadesForm['preu']) === '') {
            $errors['preu'] = "El preu és obligatori";
        } else {
            $preu = (float) $dadesForm['preu'];
            // Normalizar a 2 decimals
            $preu = number_format($preu, 2, '.', '');

            if (!preg_match('/^\d+\.\d{2}$/', $preu)) {
                $errors['preu'] = "El preu ha de ser amb decimals (ej: 10.55)";
            }
        }


        
        if (!empty($errors)) {
            return array(
                'success' => false,
                'errors' => $errors
            );
        } else {
            return array(
                'success' => true,
                'errors' => array()
            );
        }
    }
}