<table class="mb-5">
    <tbody>
        <tr >
            <td class="w-30"></td>
            <td class="w-40 text-center" style="padding-bottom: 5px;">
                <span class="title visibility-hidden">{{ trans('receipt.print.N335.title') }}</span>
            </td>
            <td rowspan="3" class="text-end w-30 fs-20" style="padding-top: 25px">
                <span style="visibility: hidden;">{{ trans('app.stt') }}G　</span>
                <span style="font-size: 26px; visibility: hidden;">{{ $receipt->code }}</span>
            </td>
        </tr>
        <tr >
            <td class="w-25"></td>
            <td class="w-40 text-center" style="padding-top: 35px;">
                <span style="font-size: 20px">{{ $year }}</span>　　<span style="visibility: hidden; ">{{ trans('app.year') }}</span>
                <span style="font-size: 20px">{{ $month }}</span>　　<span style="visibility: hidden;">{{ trans('app.month') }}</span>
                <span style="font-size: 20px">{{ $day }}</span>　　<span style="visibility: hidden; ">{{ trans('app.day') }}</span>
            </td>
        </tr>
        <tr>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
