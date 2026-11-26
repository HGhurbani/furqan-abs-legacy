<tr>
    <td colspan="12"><strong>'.PASSENGERDETAILS.'</strong></td>
</tr>
<tr>
    <td><strong>'.REMARKS.':</strong></td>
    <td>'.$row->vRemarks.'</td>
</tr>

<tr>
    <td><strong>' . SRNO . '</strong></td>
    <td><strong>' . PASSENGER . '</strong></td>
    <td><strong>' . FARE . '</strong></td>
    <td><strong>' . COMM . '</strong></td>
    <td><strong>' . COMMAMT . '</strong></td>
    <td><strong>'.FCTAX.':</strong></td>
    <td><strong>&nbsp;</strong></td>
    <td><strong>'.FCTAX.':</strong></td>
    <td><strong>'.FCTAX.':</strong></td>
    <td><strong>'.TOTALTAX.':</strong></td>
    <td><strong>'.NETTOVENDOR.':</strong></td>
</tr>';

<tr>
    <td></td>
    <td>'.$row->vPass.'</td>
    <td>'.$row->vFCTax.'</td>
    <td>'.$row->nComm.'</td>
    <td>'.$row->nORCAmt.'</td>
    <td>'.$row->vTax1.'</td>
    <td><strong>&nbsp;</strong></td>
    <td>'.$row->vTax2.'</td>
    <td>'.$row->vTax3.'</td>
    <td>'.$row->vTaxTotal.'</td>
    <td>'.$row->nNetVendor.'</td>
</tr>