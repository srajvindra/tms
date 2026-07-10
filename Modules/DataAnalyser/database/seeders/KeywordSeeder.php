<?php

namespace Modules\DataAnalyser\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DataAnalyser\Models\DomainCategory;
use Modules\DataAnalyser\Models\Keyword;

class KeywordSeeder extends Seeder
{
    /**
     * Keyword regex patterns keyed by category name, in classifier matching priority order.
     *
     * @return array<string, string>
     */
    public static function patternsByCategory(): array
    {
        return [
            'AI' => '~\bA\.?I\.?\b|artificial intelligence|ChatGPT|GPT-?[0-9o]|\bLLMs?\b|OpenAI|Anthropic|\bClaude\b|Gemini|Copilot|machine learning|deep learning|neural network|generative|DeepSeek|Midjourney|chat-?bot|hugging ?face|\bGrok\b|Sam Altman|data scien|\bNLP\b|stable diffusion|large language|supervised|reinforcement learning|recommender|MLOps|\bGAN\b|transformer \(~i',
            'Development' => '~Laravel|\bPHP\b|MySQL|Postgre?s(QL)?|JavaScript|TypeScript|Python|\bnpm\b|\bAPI\b|\bSQL\b|Linux|Ubuntu|Debian|CentOS|Docker|\bgit\b|GitHub|\bCSS\b|HTML|JSON\b|regex|Apache|nginx|\bSDK\b|\bCLI\b|framework|database|\bqueries\b|\bquery\b|front-?end|back-?end|coding|programming|developers?\b|command line|shell script|\bbash\b|cron\b|webhook|OAuth|\bSSO\b|single sign-?on|Livewire|Vue\b|React\b|Alpine\.?js|Tailwind|jQuery|Symfony|CakePHP|WordPress|plugin|Kubernetes|\bAWS\b|\bS3\b|\bEC2\b|Lambda|DevOps|localhost|\bserver\b|debugg?|compil|runtime|middleware|migration\b|Eloquent|artisan|composer\b|package\b|library\b|\brepo\b|open[- ]source|CloudWatch|Redis|Elasticsearch|GraphQL|REST(ful)? API|endpoint|SSL|TLS\b|DNS\b|\bIP address|subnet|virtualization|hypervisor|encryption|auth(entication|orization)|LDAP|SFTP|\bFTP\b|SSH\b|\bVPS\b|cPanel|htaccess|\bcode\b|\w+\.js\b~i',
            'Sports' => '~cricket|\bIPL\b|\bODI\b|\bT20\b|World Cup|Kohli|Rohit Sharma|Dhoni|Bumrah|Tendulkar|Gavaskar|wicket|batsman|batter\b|bowler|BCCI|football|FIFA|Premier League|Messi|Ronaldo|Neymar|tennis|Wimbledon|Djokovic|Nadal|Federer|Olympic|\bF1\b|Formula 1|Grand Prix|Verstappen|Lewis Hamilton|McLaren|Red Bull Racing|badminton|hockey|kabaddi|chess|Gukesh|Magnus Carlsen|wrestl|boxing|\bmedal\b|athlete|\bNBA\b|Super Bowl|marathon|Test match|innings|ESPN|Champions Trophy|Asia Cup|La Liga|UEFA|goalkeeper|Sindhu|Neeraj Chopra|hat-?trick|Ashes\b|County Championship|WWE|UFC|Grandmaster|Shubman Gill|Rishabh Pant|Jadeja|Mohammed Shami|Siraj|Hardik Pandya|Jasprit|\bspinner\b|\bpacer\b|\bPCB\b|Edgbaston|Lord.s Test|Test (team|debut|series|squad|match|cap)|dressing room|Alonso|Aston Martin|Sebastian Vettel|Leclerc|Norris\b|Piastri|Sainz\b|paddock|pit ?lane|qualifying session|pole position|Ravindra|Ashwin|Rahane|Pujara|Dravid|Ganguly|Sourav|Sehwag|Yuvraj|stumps\b|run-out|half-century|Ranji|Duleep Trophy|Border-Gavaskar|selectors~i',
            'movie' => '~\bmovies?\b|\bfilms?\b|box ?office|trailer|teaser|Bollywood|Hollywood|\bOTT\b|Netflix|web series|actor|actress|Shah ?Rukh|Salman Khan|Aamir Khan|Amitabh|Deepika|Ranbir|Ranveer|Alia Bhatt|Priyanka Chopra|Akshay Kumar|Hrithik|Kangana|Rajinikanth|blockbuster|IMDb|cinema|biopic|\bOscars?\b|Cannes|filmmaker|screenplay|Bigg Boss|TV show|sitcom|Marvel|DC Studios|Star Wars|anime\b|K-drama|Karan Johar|Bhansali|Rajamouli|Prabhas|Allu Arjun|Vicky Kaushal|Katrina|Kareena|Aishwarya|documentary|streaming~i',
            'crime' => '~murder|\brape\b|molest|arrest|\bscam\b|fraud|robbery|\btheft\b|kidnap|abduct|assault|convict|jailed|\bjail\b|\bFIR\b|smuggl|stabbed|shot dead|gunman|acid attack|dowry|extortion|bribe|lynch|terror attack|terrorist|gangster|mafia|\bheist\b|hostage|rioters?|\brapist\b|\bkiller\b|homicide|manhunt|absconding|chargesheet|\bNIA\b|\bCBI\b|\bED raids?\b|money launder|drug bust|narcotics|human trafficking|honour killing|shootout|encounter killing|cyber ?crime|hacker arrested|\bkill(ed|ing|er)?\b|shot (in|at)\b|suicide|\bstalk(er|ing)|acquitted|life imprisonment|death penalty|gang-?rape|chain-?snatch|burglar|loot(ed|ers)?\b|\bassassin~i',
            'health' => '~health|fitness|workout|\bgym\b|\bdiet\b|weight loss|obesity|\bdoctors?\b|cancer|diabet|heart attack|cardiac|cardio\b|blood pressure|cholesterol|\byoga\b|protein|vitamin|mental health|anxiety|depression|disease|\bvirus\b|covid|vaccine|hospital|surgery|kidney|liver\b|nutrition|calorie|exercise|ayurved|dementia|alzheimer|stroke\b|immunity|wellness|longevity|therapist|symptom|diagnos|tumou?r|insomnia|gut health|skincare|hair loss~i',
            'nature' => '~wildlife|\btigers?\b|\blions?\b|elephant|cheetah|leopard|\bsnakes?\b|crocodile|\bbirds?\b|forest|jungle|climate change|environment|earthquake|cyclone|tsunami|volcano|\bspecies\b|sanctuary|national park|\bocean\b|\bwhales?\b|sharks?\b|dolphin|panda|\bzoo\b|animals?\b|glacier|coral|rainforest|biodiversity|conservation|migratory|habitat|monsoon|heatwave|wildfire|Himalaya|\bweather\b|\bIMD\b|rainfall|heavy rain|heatwave|cold wave|\bfog\b|humidity~i',
            'auto' => '~\bSUV\b|sedan|hatchback|\bcars?\b|\bbikes?\b|motorcycle|scooter|Maruti|Hyundai|Mahindra\b|Tata Motors|Royal Enfield|\bKia\b|Toyota|Honda\b|Tesla|\bEVs?\b|electric vehicle|mileage|test drive|Nexon|Creta|\bThar\b|Harley|BMW\b|Mercedes|Audi\b|Lamborghini|Ferrari|Porsche|Skoda|Volkswagen|Nissan|Renault|Citroen|MG Motor|Bajaj\b|TVS\b|Hero MotoCorp|Yamaha|Kawasaki|Ducati|automaker|facelift|bookings open|cc engine|airbags~i',
            'vocab' => '~definition|\bmeaning\b|synonym|antonym|etymology|pronunciation|\bidioms?\b|\bgrammar\b|Merriam|thesaurus|word of the day|\bphrases?\b~i',
            'Career' => '~\bjobs?\b|interview|resume|\bhiring\b|recruit|salary|lay-?offs?|laid off|career|internship|appraisal|work[- ]from[- ]home|\bWFH\b|freelanc|upskill|H-?1B|employees?\b|workplace|LinkedIn|promotion\b|notice period|joining bonus|campus placement|job market|quiet quitting|moonlighting~i',
            'place' => '~tourist|tourism|travel|itinerary|places to visit|destination|taj mahal|vacation|honeymoon|trek\b|trekking|pilgrimage|\btemples?\b|heritage site|UNESCO|island\b|beach\b|hill station|Maldives|Switzerland|Dubai\b|Bali\b|Goa\b|Manali|Shimla|Ladakh|Kashmir valley|monument~i',
            'individuals' => '~\bmeet\b|who is\b|who was\b|biography|billionaire|\bCEO\b|founder|\bIAS\b officer|IAS topper|success story|richest (man|woman|person)|net worth|prodigy|entrepreneur\b|\bIITian\b|dropout who|inspiring journey~i',
            'private or corporate' => '~\bInc\.?\b|\bLtd\b|\bCorp\b|Corporation|startup|acquisition|merger|\bIPO\b|valuation|funding round|quarterly (results|earnings)|market cap|Reliance|Adani|Infosys|\bTCS\b|Wipro\b|Google\b|Microsoft|Apple\b|Amazon\b|\bMeta\b|Facebook|Samsung|Blackstone|revenue|stocks?\b|shares?\b|Sensex|Nifty|shareholders?|conglomerate|subsidiary|company\b|firm\b|brand\b~i',
        ];
    }

    /**
     * Wikipedia-title regex patterns keyed by type, each mapped to the category
     * it resolves to, in classifier matching priority order.
     *
     * @return array<string, array{category: string, pattern: string}>
     */
    public static function wikiPatternsByType(): array
    {
        return [
            'wiki_skip' => [
                'category' => 'Other',
                'pattern' => '~^(Template:|Category:|Wikipedia:|Portal:|List of|Help:|File:)~i',
            ],
            'wiki_tech' => [
                'category' => 'Development',
                'pattern' => '~SQL|database|software|programming|language\)|framework|protocol|server|computing|algorithm|data\b|\bweb\b|internet|Linux|Unix|\bAPI\b|encryption|compiler|operating system|file system|cloud|DevOps|middleware|markup|hypervisor|kernel|cryptograph|version control|search engine|Apache|\bQuery\b|Groovy|sign-?on|virtualization|\bmail\b|email|servers?\b|subnet|network|coding|configuration|authentication|OAuth|LDAP|file system|URI\b|DNS\b|TCP|HTTP|cache|storage|scheduling|scripting|open-source~i',
            ],
            'wiki_corp' => [
                'category' => 'private or corporate',
                'pattern' => '~\bInc\.?\b|Corporation|Technolog|\bLtd\b|Company|Ventures|Capital|\bBank\b|\bGroup\b|\bCorp\b|Systems|Holdings|Partners|Airlines|Motors|Industries|Enterprises|Pharma|Insurance|Consulting|Networks|Semiconductor|Studios|Entertainment|\bLabs\b|\bLLC\b~i',
            ],
            'wiki_place' => [
                'category' => 'place',
                'pattern' => '~district|Area Development|\bcity\b|province|\bregion\b|Empire|\bstate\b|\bfort\b|temple|national park|\briver\b|\blake\b|mountain~i',
            ],
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sortOrder = 0;

        foreach (self::patternsByCategory() as $name => $pattern) {
            $category = $this->findOrCreateCategory($name);

            Keyword::query()->updateOrCreate(
                ['domain_category_id' => $category->id, 'type' => 'title'],
                ['pattern' => $pattern, 'sort_order' => $sortOrder++, 'is_active' => true],
            );
        }

        $sortOrder = 0;

        foreach (self::wikiPatternsByType() as $type => $wikiPattern) {
            $category = $this->findOrCreateCategory($wikiPattern['category']);

            Keyword::query()->updateOrCreate(
                ['domain_category_id' => $category->id, 'type' => $type],
                ['pattern' => $wikiPattern['pattern'], 'sort_order' => $sortOrder++, 'is_active' => true],
            );
        }
    }

    private function findOrCreateCategory(string $name): DomainCategory
    {
        return DomainCategory::query()->firstOrCreate(
            ['name' => $name],
            [
                'sort_order' => ((int) DomainCategory::query()->max('sort_order')) + 1,
                'is_active' => true,
            ],
        );
    }
}
