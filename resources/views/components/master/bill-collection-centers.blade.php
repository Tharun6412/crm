<div>
    <table class="table table-borderless" style="table-layout: fixed; width: 100%;border: 1px solid #000000;border-spacing: 0;border-collapse: collapse;margin-bottom: 1px;margin-top: 1px;">
        <thead>
            <tr>
                <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">GA Name</th>
                <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 15%;text-align: center;">Name of bill collection center</th>
                <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 30%;text-align: center;">Address of bill collection center</th>
                <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 30%;text-align: center;">Name and Contact Number</th>
                <th style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;width: 10%;text-align: center;">Timings</th>
            </tr>
        </thead>
        <tbody>
            @if ($centers->isNotEmpty())
                @foreach ($centers as $center)
                    <tr>
                        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">{{ $center->ga->name }}</td>
                        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">{{ $center->name }}</td>
                        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">{{ $center->address }}</td>
                        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">{{ $center->contact_person }}, {{ $center->contact_mobile}}</td>
                        <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">{{ $center->timings }}</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Agiripalli</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs No: 86/2D2, Chopparametla, Agiripalli, Eluru (DT), AP - 521211</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rajamahendravarapu Eswara Rao,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Nunna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs. No: 734/3B3, Nunna bypass road, Nunna, Vijayawada, Ap-521212</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Venkateswara rao Rasuri,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Agiripalli</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs No: 86/2D2, Chopparametla, Agiripalli, Eluru (DT), AP - 521211</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rajamahendravarapu Eswara Rao,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Nuzvid </td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 25/203/1&2, Gangadhar rao Hospital Road, NSP Colony, Nuzvid, Eluru (DT), Ap-521201</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">K Satyannarayana,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Gannavaram</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 12-216, Beside Sub registar office, Near Konaye Cheruvu, Gannavarm, Krishna, Ap-521101</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Chitturi Ashok,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Tadigadapa & Yanamalakuduru</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">RS.No: 105/6,105/7, YSR Tadigadapa, Yanamalakuduru, Penamaluru, Ap-520007</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Pamarthi Siva Nagaraju,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Avanigadda</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 6-196, Ward No 9, Avanigadda, Krishna (DT), Ap-521121</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Lakshminadha Rao,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Nagayalanka</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">RS.No: 481/1, 491/2&3, 492/1, Vakkabatlavari Palem, Nagayalanka, Krishna-521120</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Ashok Kondeti,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Gudlavalleru</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">D.No: 10-122, Near by Avasavlli rest home , Main road, Gudlavalleru, Krishna (DT), Ap-521356</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Marrivada Gangabhavni,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
                <tr>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Krishna</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Pedana</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Rs No: 17/18-8-16, Ward No:8, NTR Colony, Pedana, Krishna (DT), AP-531366</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">Gunja Nagalakshmi,  040-46565 555</td>
                    <td style="border-bottom: 1px solid #000000;border-right: 1px solid #000000;font-size: 8px;">10:00 to 17:00</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>