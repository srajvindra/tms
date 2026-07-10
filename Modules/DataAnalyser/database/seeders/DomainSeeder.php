<?php

namespace Modules\DataAnalyser\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\DataAnalyser\Models\Domain;
use Modules\DataAnalyser\Models\DomainCategory;

class DomainSeeder extends Seeder
{
    private const DEV_DOMAINS = 'stackoverflow.com stackexchange.com dba.stackexchange.com serverfault.com superuser.com
github.com docs.github.com gist.github.com gitlab.com bitbucket.org
docs.aws.amazon.com aws.amazon.com repost.aws console.aws.amazon.com
laravel.com livewire.laravel.com laracasts.com laravel-news.com spatie.be symfony.com alpinejs.dev
php.net phpunit.de getcomposer.org packagist.org book.cakephp.org cakephp.org
dev.mysql.com mysql.com postgresql.org sqlite.org redis.io mongodb.com
geeksforgeeks.org developer.mozilla.org w3schools.com css-tricks.com dev.to freecodecamp.org
learn.microsoft.com docs.microsoft.com visualstudio.com code.visualstudio.com
cloud.google.com developers.google.com firebase.google.com
developer.bigcommerce.com support.bigcommerce.com developer-docs.amazon.com advertising.amazon.com
sellercentral.amazon.com developer.walmart.com docs.shipstation.com shipstation.com help.shipstation.com
jenkins.io docker.com docs.docker.com hub.docker.com kubernetes.io nginx.org httpd.apache.org apache.org
pandas.pydata.org numpy.org python.org docs.python.org pypi.org realpython.com
npmjs.com nodejs.org reactjs.org react.dev vuejs.org angular.io jquery.com getbootstrap.com tailwindcss.com
regex101.com jsfiddle.net codepen.io replit.com leetcode.com hackerrank.com
digitalocean.com baeldung.com tutorialspoint.com javatpoint.com sitepoint.com
git-scm.com atlassian.com jetbrains.com phpstorm sourceforge.net
sandbox.fluidpay.com fluidpay.com stripe.com docs.stripe.com postman.com elastic.co grafana.com
caniuse.com json.org yaml.org graphql.org swagger.io wordpress.org codeigniter.com
news.ycombinator.com bobbyhadz.com javascripttutorial.net postgresqltutorial.com postgis.net
docs.djangoproject.com djangoproject.com phpstan.org linuxize.com askubuntu.com unix.stackexchange.com
howtogeek.com fullcalendar.io floating-ui.com framework.zend.com kinsta.com tinkerwell.app easydcim.com
developer.intuit.com iriscrm.com nmi.com snowflake.com godaddy.com in.godaddy.com databasestar.com
basedash.com pyimagesearch.com omar2cloud.github.io enterprisedb.com laravel-livewire.com filamentphp.com
statamic.com beyondco.de tighten.com freek.dev stitcher.io mattstauffer.com daily.dev hackernoon.com
smashingmagazine.com sitground.com serversforhackers.com ploi.io forge.laravel.com vapor.laravel.com
scotch.io stackabuse.com honeybadger.io sentry.io bugsnag.com circleci.com travis-ci.com
sell.amazon.in sellercentral.amazon.in supplier.walmart.com developer.ebay.com developers.facebook.com
developer.paypal.com developer.squareup.com docs.wpvip.com wpbeginner.com wpengine.com cpanel.net
plesk.com phoenixnap.com tecmint.com nixcraft.com cyberciti.biz baeldung.com json-schema.org';

    private const AI_DOMAINS = 'openai.com platform.openai.com chat.openai.com chatgpt.com community.openai.com
huggingface.co anthropic.com claude.ai claude.com ollama.com langchain.com python.langchain.com
kaggle.com colab.research.google.com tensorflow.org pytorch.org deepmind.google
midjourney.com stability.ai perplexity.ai gemini.google.com bard.google.com
analyticsindiamag.com machinelearningmastery.com towardsdatascience.com deeplearning.ai groq.com
civitai.com replicate.com together.ai mistral.ai cohere.com pinecone.io weaviate.io';

    private const VOCAB_DOMAINS = 'merriam-webster.com dictionary.com vocabulary.com thesaurus.com
dictionary.cambridge.org wordreference.com etymonline.com en.wiktionary.org collinsdictionary.com
oxfordlearnersdictionaries.com ludwig.guru wordhippo.com rhymezone.com urbandictionary.com grammarly.com powerthesaurus.org';

    private const SHOP_DOMAINS = 'flipkart.com myntra.com ebay.com aliexpress.com meesho.com ajio.com croma.com
snapdeal.com bigbasket.com ikea.com decathlon.in reliancedigital.in shopclues.com etsy.com
firstcry.com nykaa.com pepperfry.com urbanladder.com boat-lifestyle.com';

    private const MOVIE_DOMAINS = 'imdb.com hotstar.com netflix.com primevideo.com bookmyshow.com rottentomatoes.com
boxofficemojo.com bollywoodhungama.com koimoi.com pinkvilla.com filmfare.com letterboxd.com
zee5.com sonyliv.com jiocinema.com';

    private const SPORT_DOMAINS = 'espncricinfo.com cricbuzz.com formula1.com fifa.com nba.com icc-cricket.com
espn.com espn.in skysports.com goal.com atptour.com wtatennis.com olympics.com iplt20.com
bcci.tv motorsport.com autosport.com the-race.com planetf1.com racefans.net';

    private const AUTO_DOMAINS = 'cardekho.com carwale.com autocarindia.com zigwheels.com bikewale.com
overdrive.in team-bhp.com motoroids.com rushlane.com gaadiwaadi.com carandbike.com
motorbeam.com bikedekho.com acko.com/drivex';

    private const HEALTH_DOMAINS = 'healthline.com webmd.com mayoclinic.org medicalnewstoday.com nih.gov
who.int practo.com 1mg.com netmeds.com apollo247.com healthifyme.com verywellhealth.com
health.harvard.edu clevelandclinic.org';

    private const CAREER_DOMAINS = 'naukri.com indeed.com glassdoor.com glassdoor.co.in monster.com monsterindia.com
shine.com instahyre.com angel.co wellfound.com hired.com interviewbit.com levels.fyi
foundit.in timesjobs.com udemy.com coursera.org edx.org pluralsight.com teamblind.com skillshare.com upgrad.com simplilearn.com';

    private const PLACE_DOMAINS = 'tripadvisor.com tripadvisor.in makemytrip.com booking.com airbnb.com agoda.com
goibibo.com lonelyplanet.com incredibleindia.org holidify.com thrillophilia.com yatra.com
cleartrip.com irctc.co.in maps.google.com wikitravel.org wikivoyage.org';

    private const PORTAL_DOMAINS = 'mail.google.com gmail.com outlook.live.com outlook.office.com web.whatsapp.com
web.telegram.org drive.google.com calendar.google.com keep.google.com photos.google.com
onedrive.live.com dropbox.com accounts.google.com login.microsoftonline.com app.asana.com asana.com trello.com notion.so slack.com app.slack.com zoom.us meet.google.com docs.google.com sheets.google.com';

    /**
     * Domain lists keyed by category name, in classifier matching priority order.
     *
     * @return array<string, list<string>>
     */
    public static function domainsByCategory(): array
    {
        return [
            'vocab' => self::splitDomains(self::VOCAB_DOMAINS),
            'AI' => self::splitDomains(self::AI_DOMAINS),
            'Development' => self::splitDomains(self::DEV_DOMAINS),
            'Career' => self::splitDomains(self::CAREER_DOMAINS),
            'shop' => self::splitDomains(self::SHOP_DOMAINS),
            'movie' => self::splitDomains(self::MOVIE_DOMAINS),
            'Sports' => self::splitDomains(self::SPORT_DOMAINS),
            'auto' => self::splitDomains(self::AUTO_DOMAINS),
            'health' => self::splitDomains(self::HEALTH_DOMAINS),
            'place' => self::splitDomains(self::PLACE_DOMAINS),
            'portals' => self::splitDomains(self::PORTAL_DOMAINS),
        ];
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seenHosts = [];
        $sortOrder = 0;

        foreach (self::domainsByCategory() as $name => $hosts) {
            $category = DomainCategory::query()->updateOrCreate(
                ['name' => $name],
                ['sort_order' => $sortOrder++, 'is_active' => true],
            );

            foreach ($hosts as $host) {
                if (isset($seenHosts[$host])) {
                    continue;
                }

                $seenHosts[$host] = true;

                Domain::query()->updateOrCreate(
                    ['host' => $host],
                    ['domain_category_id' => $category->id, 'is_active' => true],
                );
            }
        }
    }

    /**
     * @return list<string>
     */
    private static function splitDomains(string $whitespaceSeparated): array
    {
        return preg_split('~\s+~', trim($whitespaceSeparated), -1, PREG_SPLIT_NO_EMPTY);
    }
}
