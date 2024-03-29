<?php

require_once('dte.php');

$data = json_decode('
{
  "Encabezado": {
    "Totales": {
      "TpoMoneda": false,
      "MntNeto": 1200,
      "MntExe": false,
      "TasaIVA": 19,
      "IVA": 100,
      "IVANoRet": false,
      "CredEC": false,
      "MntTotal": 12000,
      "MontoNF": false,
      "MontoPeriodo": false,
      "SaldoAnterior": false,
      "VlrPagar": false
    },
    "IdDoc": {
      "TipoDTE": 52,
      "Folio": 1,
      "TipoDespacho": 1,
      "IndTraslado": 1
    },
    "Emisor": {
      "RUTEmisor": "76192083-9",
      "RznSoc": "SASCO SpA",
      "GiroEmis": "Servicios integrales de informática",
      "Acteco": 726000,
      "DirOrigen": "Santiago",
      "CmnaOrigen": "Santiago"
    },
    "Receptor": {
      "RUTRecep": "60803000-K",
      "RznSocRecep": "Servicio de Impuestos Internos",
      "GiroRecep": "Gobierno",
      "DirRecep": "Calle 123",
      "CmnaRecep": "Santiago"
    },
    "Transporte": {
      "Patente": "ABCD12",
      "RUTTrans": "2-7",
      "Chofer": {
        "RUTChofer": "1-9",
        "NombreChofer": "Pedro"
      },
      "DirDest": "Calle 123",
      "CmnaDest": "Santiago"
    }
  },
  "Detalle": [
    {
      "NmbItem": "Conectores RJ45",
      "QtyItem": 450,
      "PrcItem": 70
    }
  ]
}', true);

$pdf = new \Dte(false);
$pdf->setFooterText();
$pdf->setLogo('./cisco_netacad_logo.png'); // debe ser PNG!
$pdf->agregar($data);
$pdf->Output('/home/jose/Projects/guia/dte__.pdf', 'F');
