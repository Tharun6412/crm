@extends('layouts.layout')
@section('title','Help')
@section('page-title','Help')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ url('spot/dashboard') }}">SPot</a></li>
@endsection
@section('page-content')
    <div class="help-title text-primary">
        <h3><i class="bi bi-search"></i>&nbsp;S - Suspect</h3>
    </div>
    <div class="help-content">
        <ul>
            <li><strong>Definition:</strong>&nbsp;A potential lead who might have a need for your product/service but hasn’t been qualified yet.</li>
            <li><strong>Sales lingo:</strong>&nbsp;“They’re on the radar, but no signal yet.”</li>
            <li><strong>Who?&nbsp;-&nbsp;</strong>&nbsp;Unconnected commercial and industrial units in your GA.</li>
            <li><strong>Examples:</strong>
                <ol>
                    <li>Factories using FO/HSD/LPG,</li>
                    <li>Hotels/restaurants using cylinders</li>
                </ol>
            </li>
            <li><strong>Action:</strong>
                <ol>
                    <li>Research,</li>
                    <li>Cold call/initial reach-out.</li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="help-title text-warning">
        <h3><i class="bi bi-lightbulb"></i>&nbsp;P – Prospect</h3>
    </div>
    <div class="help-content">
        <ul>
            <li><strong>Definition:</strong>&nbsp;A lead who has shown some level of interest or engagement.</li>
            <li><strong>Sales lingo:</strong>&nbsp;“They’ve opened the door slightly.”</li>
            <li><strong>Who?</strong>&nbsp;Units that responded to your outreach or awareness.</li>
            <li><strong>Examples:</strong>
                <ol>
                    <li>They accepted a meeting.</li>
                    <li>Asked for a product brochure or sample.</li>
                    <li>Factory asking about payback for switching fuel, hotel keen on savings over LPG.</li>
                </ol>
            </li>
            <li><strong>Action:</strong>
                <ol>
                    <li>Qualify Needs-Collect basic energy consumption details (FO/HSD/LPG volume).</li>
                    <li>Evaluate fit/ build rapport-Explain benefits: cost savings, uninterrupted supply, compliance (green fuel), safety. Share ROI estimates, government push for clean fuel.</li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="help-title text-danger">
        <h3><i class="bi bi-bullseye"></i>&nbsp;A – Approach</h3>
    </div>
    <div class="help-content">
        <ul>
            <li><strong>Definition:</strong>&nbsp;You’re actively pitching, demoing, or presenting your offering.</li>
            <li><strong>Sales lingo:</strong>&nbsp;“Now we’re talking business.”</li>
            <li><strong>Who?</strong>&nbsp;Evaluating a serious proposal and technical fitment.</li>
            <li><strong>Examples:</strong>
                <ol>
                    <li>You're doing a trial installation.</li>
                    <li>Formal proposal is under discussion.</li>
                    <li>Factory gives consent for site survey, discusses daily offtake needs.</li>
                </ol>
            </li>
            <li><strong>Action:</strong>
                <ol>
                    <li><strong>Technical-</strong>&nbsp;Conduct load assessment & line sizing (SCM/day).</li>
                    <li><strong>Offer-</strong>&nbsp;Propose dual-fuel burner options for phased transition- Showcase value prop, highlight differentiation, Show successful case studies in similar segments.</li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="help-title text-info">
        <h3><i class="bi bi-currency-exchange"></i>&nbsp;N – Negotiation</h3>
    </div>
    <div class="help-content">
        <ul>
            <li><strong>Definition:</strong>&nbsp;Customer is interested, but discussing terms, price, quantity, etc.</li>
            <li><strong>Sales lingo:</strong>&nbsp;“The deal’s hot but not closed.”</li>
            <li><strong>Who?</strong>&nbsp;Actively discussing commercials or terms.</li>
            <li><strong>Examples:</strong>
                <ol>
                    <li>Price negotiations, technical clarifications.</li>
                    <li>Finalizing commercial terms with procurement.</li>
                </ol>
            </li>        	
            <li><strong>Action:&nbsp;</strong>
                <ol>
                    <li><strong>GSA-</strong>&nbsp;legal/contract alignment, flexible offer structuring, negotiate contract clauses (offtake guarantees, payment cycles).</li>
                    <li><strong>Pricing-</strong>&nbsp;Value selling, Offer volume-linked slab pricing or discounts for early adopters.</li>
                    <li><strong>Knowledge Partner-</strong>&nbsp;Bring in technical/operations team to jointly address conversion challenges.</li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="help-title text-success">
        <h3><i class="bi bi-box-seam"></i>&nbsp;C – Closure</h3>
    </div>
    <div class="help-content">
        <ul>
            <li><strong>Definition:</strong>&nbsp;Deal has been won or lost.</li>
            <li><strong>Sales lingo:</strong>&nbsp;“Signed, sealed, (hopefully) delivered.”</li>
            <li><strong>Who?</strong>&nbsp;Decision made – contract signed or dropped.</li>
            <li><strong>Examples:</strong>
                <ol>
                    <li><strong>GSA</strong> received or officially declined.</li>
                </ol>
            </li>	        
            <li><strong>Action:</strong>
                <ol>
                    <li><strong>Win&nbsp;→</strong>&nbsp;Onboarding & execution.</li>
                    <li><strong>Lose&nbsp;→</strong>&nbsp;Conduct post-mortem, learn and move on.</li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="help-title  text-secondary">
        <h3><i class="bi bi-rocket-takeoff"></i>&nbsp;O – Order</h3>
    </div>
    <div class="help-content">
        <ul>
            <li><strong>Definition:</strong>&nbsp;The first order has been booked — revenue begins.</li>
            <li><strong>Sales lingo:</strong>&nbsp;“The cash register rings.”</li>
            <li><strong>Who?</strong>&nbsp;Connection activated; gas starts flowing.</li>
            <li><strong>Examples:</strong>&nbsp;
                <ol>
                    <li>First tanker delivery of LNG</li>
                </ol>
            </li>
            <li><strong>Action:</strong>&nbsp;
                <ol>
                    <li><strong>Execution-</strong>&nbsp;Connection deposit, Last mile connectivity and MRS</li>
                    <li><strong>Commissioning:</strong>&nbsp;Consumption deposit, Trails, provide onsite training, Share SOPs for daily usage, metering & safety. Ensure good product experience, prompt support.</li>
                </ol>
            </li>
        </ul>
    </div>
    <div class="table-responsive spot-table mt-2">
        <table class="table table-bordered table-hover table-striped table-sm align-middle">
            <thead>
                <tr class="spot-table-bg">
                    <th>Stage</th>
                    <th>Objective</th>
                    <th>Key Sales Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Suspect</td>
                    <td>Identify potential industrial/commercial users</td>
                    <td>Cold mapping, industry connects</td>
                </tr>
                <tr>
                    <td>Prospect</td>
                    <td>Gauge initial interest</td>
                    <td>Qualify via fuel cost comparison</td>
                </tr>
                <tr>
                    <td>Approach</td>
                    <td>Customize proposal</td>
                    <td>Site survey, usage profiling</td>
                </tr>
                <tr>
                    <td>Negotiation</td>
                    <td>Discuss terms</td>
                    <td>Finalize pricing & contracts</td>
                </tr>
                <tr>
                    <td>Closure</td>
                    <td>Sign or drop</td>
                    <td>Secure LoA or log reason for loss</td>
                </tr>
                <tr>
                    <td>Order</td>
                    <td>Commission & start supply</td>
                    <td>Onboarding, training, monitor delivery</td>
                </tr>
            </tbody>
        </table>
    </div>
    <style type="text/css">
        .help-title h3 {
            /* border-bottom:1px solid #3376BC; */
            font-weight: bold;
            display: inline-block;
            font-size: 20px;
        }
    </style>
@endsection