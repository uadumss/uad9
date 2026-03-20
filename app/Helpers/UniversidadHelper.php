<?php

namespace App\Helpers;

class UniversidadHelper
{
    /**
     * Catálogo de universidades de Bolivia
     * Retorna el tipo de institución: 'Pública', 'Privada' o 'Extranjera'
     * Se uso la lista de universidades de https://www.altillo.com/universidades/universidades_bolivia.asp para el listado de universidades públicas y privadas, y se asumió que cualquier universidad no listada es extranjera.
     */
    public static function getTipoUniversidad($nombreUniversidad)
    {
        $universidadesPublicas = [
            'Universidad Mayor de San Simón' => 'UMSS',
            'UMSS' => 'UMSS',
            'Universidad Mayor de San Andrés' => 'UMSA',
            'UMSA' => 'UMSA',
            'Universidad Autónoma Gabriel René Moreno' => 'UAGRM',
            'UAGRM' => 'UAGRM',
            'UAGR' => 'UAGRM',
            'Universidad Autónoma Juan Misael Saracho' => 'UAJMS',
            'UAJMS' => 'UAJMS',
            'Universidad Técnica de Oruro' => 'UTO',
            'UTO' => 'UTO',
            'Universidad Autónoma Tomás Frías' => 'UATF',
            'UATF' => 'UATF',
            'Universidad Pública de El Alto' => 'UPEA',
            'UPEA' => 'UPEA',
            'Universidad Autónoma del Beni José Ballivián' => 'UABJB',
            'UABJB' => 'UABJB',
            'Universidad Mayor Real y Pontificia San Francisco Xavier de Chuquisaca' => 'USFX',
            'USFX' => 'USFX',
            'Universidad Andina Simón Bolívar' => 'UASB',
            'UASB' => 'UASB',
            'Universidad Nacional Siglo XX' => 'UNSXX',
            'UNSXX' => 'UNSXX',
            'Universidad Amazónica de Pando' => 'UAP',
            'UAP' => 'UAP'
        ];

        $universidadesPrivadas = [
            'Universidad Católica Boliviana San Pablo' => 'UCB',
            'UCB' => 'UCB',
            'Universidad Privada del Valle' => 'UNIVALLE',
            'UNIVALLE' => 'UNIVALLE',
            'Universidad Privada Franz Tamayo' => 'UNIFRANZ',
            'UNIFRANZ' => 'UNIFRANZ',
            'Universidad Autónoma del Beni' => 'UAB',
            'UAB' => 'UAB',
            'Universidad Privada Santa Cruz de la Sierra' => 'PRUCR',
            'PRUCR' => 'PRUCR',
            'Universidad Privada Domingo Savio' => 'UPDS',
            'UPDS' => 'UPDS',
            'Universidad Especializada de Full Tiempo' => 'UNEFT',
            'UNEFT' => 'UNEFT',
            'Universidad Tecnológica Latinoamericana' => 'UTLAT',
            'UTLAT' => 'UTLAT',
            'Universidad de Aquino Bolivia' => 'UDABOL',
            'UDABOL' => 'UDABOL',
            'Universidad de los Andes' => 'UDELOSANDES',
            'UDELOSANDES' => 'UDELOSANDES',
            'Escuela Militar de Ingeniería' => 'EMI',
            'EMI' => 'EMI',
            'Universidad Simón I. Patiño' => 'USIP',
            'USIP' => 'USIP',
            'Universidad Central' => 'UNICEN',
            'UNICEN' => 'UNICEN',
            'Uni. Priv. de Cs. Administrativas y Tecnológicas' => 'UCATEC',
            'UCATEC' => 'UCATEC',
            'Universidad Pedagógica' => 'UP',
            'UP' => 'UP',
            'Universidad Privada Abierta Latinoamericana' => 'UPAL',
            'UPAL' => 'UPAL',
            'Universidad Privada Boliviana' => 'UPB',
            'UPB' => 'UPB',
            'Universidad Técnica Privada Cosmos' => 'UNITEPC',
            'UNITEPC' => 'UNITEPC',
            'Universidad Latinoamericana' => 'ULAT',
            'ULAT' => 'ULAT',
            'Universidad Indígena Quechua Casimiro Huanca' => 'UNIBOL QUECHUA',
            'UNIBOL QUECHUA' => 'UNIBOL QUECHUA',
            'Universidad La Salle' => 'ULS',
            'ULS' => 'ULS',
            'Universidad Boliviana de Informática' => 'UBI',
            'UBI' => 'UBI',
            'Universidad Militar Mariscal Bernardino Bilbao Rioja' => 'Universidad MILITAR MARISCAL BERNARDINO BILBAO RIOJA',
            'Universidad Nur' => 'NUR',
            'NUR' => 'NUR',
            'Universidad Loyola' => 'LOYOLA',
            'LOYOLA' => 'LOYOLA',
            'Universidad Nuestra Señora de La Paz' => 'UNSLP',
            'UNSLP' => 'UNSLP',
            'Universidad Privada San Francisco de Asis' => 'USFA',
            'USFA' => 'USFA',
            'Universidad Real' => 'UREAL',
            'UREAL' => 'UREAL',
            'Universidad Salesiana de Bolivia' => 'USALESIANA',
            'USALESIANA' => 'USALESIANA',
            'Universidad para la Investig. Estratégica en Bolivia' => 'UPIEB',
            'UPIEB' => 'UPIEB',
            'Universidad Tecnológica Boliviana' => 'UTB',
            'UTB' => 'UTB',
            'Universidad Unión Bolivariana' => 'UB',
            'UB' => 'UB',
            'Universidad Saint Paul' => 'USP',
            'USP' => 'USP',
            'Universidad de la Cordillera' => 'UCORDILLERA',
            'UCORDILLERA' => 'UCORDILLERA',
            'Universidad Indígena Boliviana Aymara Túpac Katari' => 'UTUPAKKATARI',
            'UTUPAKKATARI' => 'UTUPAKKATARI',
            'Universidad Indígena Tawantinsuyu' => 'UINTAN',
            'UINTAN' => 'UINTAN',
            'Universidad Privada De Oruro' => 'UNIOR',
            'UNIOR' => 'UNIOR',
            'Universidad Nacional del Oriente' => 'UNO',
            'UNO' => 'UNO',
            'Universidad Nacional Ecológica' => 'UECOLOGICA',
            'UECOLOGICA' => 'UECOLOGICA',
            'Universidad Cristiana de Bolivia' => 'UCEBOL',
            'UCEBOL' => 'UCEBOL',
            'Universidad Empresarial Mateo Kuljis' => 'UNIKULJIS',
            'UNIKULJIS' => 'UNIKULJIS',
            'Universidad Evangélica Boliviana' => 'UEB',
            'UEB' => 'UEB',
            'Universidad de la Amazonía Boliviana' => 'UNAB',
            'UNAB' => 'UNAB',
            'Universidad para el Desarrollo y la Innovación' => 'UDI',
            'UDI' => 'UDI',
            'Universidad Privada Cumbre' => 'CUMBRE',
            'CUMBRE' => 'CUMBRE',
            'Universidad Bethesda' => 'UNIBETH',
            'UNIBETH' => 'UNIBETH',
            'Universidad Privada de Santa Cruz de la Sierra' => 'UPSA',
            'UPSA' => 'UPSA',
            'Universidad Tecnológica Privada de Santa Cruz' => 'UTEPSA',
            'UTEPSA' => 'UTEPSA',
            'Universidad Indígena Guaraní Apiaguaiki Tupa' => 'UNIBOLGUARANI',
            'UNIBOLGUARANI' => 'UNIBOLGUARANI',
            'Universidad Unidad' => 'UUNIDAD',
            'UUNIDAD' => 'UUNIDAD',
        ];

        if (empty($nombreUniversidad)) {
            return null;
        }

        $busqueda = self::normalizarTexto($nombreUniversidad);

        // Buscar en universidades públicas
        foreach ($universidadesPublicas as $nombre => $sigla) {
            $nombreNormalizado = self::normalizarTexto($nombre);
            $siglaNormalizada = self::normalizarTexto($sigla);
            
            if ($nombreNormalizado === $busqueda || $siglaNormalizada === $busqueda) {
                return 'Pública';
            }
        }

        // Buscar en universidades privadas
        foreach ($universidadesPrivadas as $nombre => $sigla) {
            $nombreNormalizado = self::normalizarTexto($nombre);
            $siglaNormalizada = self::normalizarTexto($sigla);
            
            if ($nombreNormalizado === $busqueda || $siglaNormalizada === $busqueda) {
                return 'Privada';
            }
        }

        // Si no está en la lista, es extranjera
        return 'Extranjera';
    }

    /**
     * Normaliza el texto: sin acentos, mayúsculas y sin espacios extras
     */
    private static function normalizarTexto($texto)
    {
        // Remover espacios extras primero
        $texto = trim($texto);
        
        // Remover acentos ANTES de convertir a mayúsculas
        $texto = self::removerAcentos($texto);
        
        // Convertir a mayúsculas
        $texto = strtoupper($texto);
        
        return $texto;
    }

    /**
     * Remueve acentos del texto
     */
    private static function removerAcentos($texto)
    {
        $acentos = array(
            'Á' => 'A', 'á' => 'a',
            'É' => 'E', 'é' => 'e',
            'Í' => 'I', 'í' => 'i',
            'Ó' => 'O', 'ó' => 'o',
            'Ú' => 'U', 'ú' => 'u',
            'Ñ' => 'N', 'ñ' => 'n'
        );
        
        return strtr($texto, $acentos);
    }

    /**
     * Obtiene el color del badge según el tipo
     */
    public static function getColorBadge($tipo)
    {
        switch ($tipo) {
            case 'Pública':
                return 'badge-success';
            case 'Privada':
                return 'badge-warning';
            case 'Extranjera':
                return 'badge-info';
            default:
                return 'badge-secondary';
        }
    }
}
