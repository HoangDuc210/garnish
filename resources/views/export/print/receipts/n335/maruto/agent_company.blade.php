<table style="margin-top: 33px;">
    <tbody>
        <tr>
            <td  colspan="2"></td>
            <td  class=""></td>
        </tr>
        <tr>
            <td class="vertical-bottom" >
                {{ $agent->name }}
            </td>
            <td class="text-end" >
            </td>
            <td  class="text-end company" >
                <span class="visibility-hidden">{{ $company->name }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2"  rowspan="3"></td>
            <td  class="text-end company pl-3 " style="width: 40% !important;">
                <span class="visibility-hidden">{{ trans('app.zip_code') }} {{ $company->post_code }}</span>
            </td>
        </tr>
        <tr>
            <td  class="text-end company pl-3">
                <span class="visibility-hidden">{{ $company->address }}</span>
            </td>
        </tr>
        <tr>
            <td  class="text-end company pl-3">
                <span class="visibility-hidden">{{ trans('company.tel') }} {{ $company->tel }}</span>
            </td>
        </tr>
    </tbody>
</table>
