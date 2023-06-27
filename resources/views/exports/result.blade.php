<table>
    @foreach ($articles as $article)
        <tr>
            <td style="background-color:yellow;">{{ $article->no }}</td>
            <td colspan="4" style="background-color: yellow; text-align:center;">{{ $article->title }}</td>
        </tr>
        <tr>
            <th>No</th>
            <th>Question</th>
            <th>User</th>
            <th>Score</th>
            <th>Sum</th>
        </tr>
        @php
            $total = 0;
        @endphp
        @if (count($article->article_user) != 0)     
            @foreach ($questionaires as $questionaire)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $questionaire->question }}</td>
                <td>
                    @foreach ($article->article_user as $item)
                        {{ $item->user->name }}<br>
                    @endforeach
                </td>
                <td>
                    @php
                        $sum = 0;
                    @endphp
                    @foreach ($article->article_user as $item)
                        @if (count($item->questionaires) == 0)
                            -<br>
                        @else
                            @foreach ($item->questionaires as $item2)
                                @if ($item2->questionaire_id == $questionaire->id)
                                    {{ $item2->score }}<br>
                                    @php
                                        $sum += $item2->score;
                                    @endphp
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                </td>
                <td>
                    {{ $sum }}
                    @php
                        $total += $sum;
                    @endphp
                </td>
            </tr>
            @endforeach
        @else
            @foreach ($questionaires as $questionaire)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $questionaire->question }}</td>
                    <td></td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            @endforeach
        @endif
        <tr>
            <td colspan="3" style="text-align:center;">Total</td>
            <td colspan="2" style="text-align:center;">{{ $total }}</td>
        </tr>
        <tr>
            <td colspan="5" style="background-color: #837D7D"></td>
        </tr>
    @endforeach
</table>