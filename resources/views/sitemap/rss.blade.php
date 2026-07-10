<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0">
    <channel>
        <title>{{ $feed['title'] }}</title>
        <link>{{ $feed['link'] }}</link>
        <description>{{ $feed['description'] }}</description>
        <lastBuildDate>{{ $feed['lastBuildDate'] }}</lastBuildDate>
        <language>en</language>
        @foreach ($feed['items'] as $item)
        <item>
            <title>{{ $item['title'] }}</title>
            <link>{{ $item['link'] }}</link>
            <description><![CDATA[{{ $item['description'] }}]]></description>
            <pubDate>{{ $item['pubDate'] }}</pubDate>
            <guid>{{ $item['link'] }}</guid>
        </item>
        @endforeach
    </channel>
</rss>
