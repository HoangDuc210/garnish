<table>
    <tr>
        <td rowspan="5" class="vertical-bottom w-60 font-bold" style="padding-left: 20px; font-size: 20px">
            <span class="company-name" style="font-size: 20px">{{ $agent->name }}</span>
        </td>
        <td class="w-40 " style="padding-left: 20px;">
            <span>{{ $company->name }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-left: 20px;" class=" w-40 ">
            <span>{{ trans('app.zip_code') }} {{ $company->post_code }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-left: 20px;" class="w-40 ">
            <span>{{ $company->address }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-left: 20px;" class="w-40 ">
            <span>{{ trans('company.tel') }} {{ $company->tel }}</span>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-left: 20px;" class="w-40 ">
            <span>{{ trans('company.fax') }} {{ $company->fax }}</span>
        </td>
    </tr>
    <tr>
        <td class="w-60" style="padding-top: 10px;">
            <span style="visibility: hidden;">下記のとおり納品いたしました。</span>
        </td>
        <td class="w-40" style="padding-left: 20px;">
            <span style="visibility:hidden;">登録番号</span>
            {{ $company->regis_number }}
        </td>
    </tr>
</table>
