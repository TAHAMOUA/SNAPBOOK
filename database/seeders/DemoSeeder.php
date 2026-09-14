<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Booking;
use App\Models\Category;
use App\Models\PhotographerProfile;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        Storage::disk('public')->makeDirectory('portfolio');
        $this->generatePortfolioImages();

        $categories = $this->ensureCategories();
        $this->ensureUser('admin@snapbook.com', 'Admin', 'SnapBook', '+212600000000', 'admin');

        $clients = [
            'sara'   => $this->ensureUser('sara@snapbook.com', 'Sara', 'El Alami', '+212611111111', 'client'),
            'youssef' => $this->ensureUser('youssef@snapbook.com', 'Youssef', 'Benkirane', '+212622222222', 'client'),
            'nadia'  => $this->ensureUser('nadia@snapbook.com', 'Nadia', 'Fassi-Fihri', '+212633333333', 'client'),
        ];

        $profiles  = $this->ensureProfiles();
        $services  = $this->ensureServices($profiles, $categories);
        $this->ensurePortfolio($profiles);
        $slots     = $this->ensureAvailabilities($profiles);
        $this->ensureBookings($clients, $services, $slots);
    }

    /* ── Users ──────────────────────────────────── */

    private function ensureUser(string $email, string $first, string $last, string $phone, string $role): User
    {
        $user = User::where('email', $email)->first();
        if ($user) {
            return $user;
        }

        $user = User::create([
            'first_name' => $first,
            'last_name'  => $last,
            'email'      => $email,
            'phone'      => $phone,
            'password'   => 'password',
            'role'       => $role,
        ]);

        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    /* ── Categories ─────────────────────────────── */

    private function ensureCategories(): array
    {
        $names = [
            'Wedding Photography',
            'Portrait Photography',
            'Event Photography',
            'Family Photography',
            'Landscape Photography',
        ];

        $cats = [];
        foreach ($names as $name) {
            $cats[$name] = Category::where('category_name', $name)->first()
                ?? Category::create(['category_name' => $name]);
        }

        return $cats;
    }

    /* ── Photographer Profiles ──────────────────── */

    private function ensureProfiles(): array
    {
        $data = [
            'ahmed' => [
                'email' => 'ahmed.tazi@snapbook.com', 'first_name' => 'Ahmed', 'last_name' => 'Tazi',
                'phone' => '+212640000001', 'city' => 'Marrakech', 'experience' => 8,
                'validation_status' => 'approved',
                'bio' => 'Specializing in wedding and event photography with over 8 years of experience capturing love stories across Morocco.',
            ],
            'fatima' => [
                'email' => 'fatima.zahra@snapbook.com', 'first_name' => 'Fatima Zahra', 'last_name' => 'Bennis',
                'phone' => '+212640000002', 'city' => 'Casablanca', 'experience' => 5,
                'validation_status' => 'approved',
                'bio' => 'Portrait and fashion photographer based in Casablanca. I bring out the best in every subject through natural light and creative direction.',
            ],
            'karim' => [
                'email' => 'karim.idrissi@snapbook.com', 'first_name' => 'Karim', 'last_name' => 'Idrissi',
                'phone' => '+212640000003', 'city' => 'Rabat', 'experience' => 12,
                'validation_status' => 'approved',
                'bio' => "Landscape and architecture photographer with 12 years of experience documenting Morocco's stunning cities and natural beauty.",
            ],
            'leila' => [
                'email' => 'leila.moussaoui@snapbook.com', 'first_name' => 'Leila', 'last_name' => 'Moussaoui',
                'phone' => '+212640000004', 'city' => 'Fes', 'experience' => 3,
                'validation_status' => 'approved',
                'bio' => 'Family and lifestyle photographer in Fes. I love capturing authentic, joyful moments between families and loved ones.',
            ],
            'omar' => [
                'email' => 'omar.alaoui@snapbook.com', 'first_name' => 'Omar', 'last_name' => 'Alaoui',
                'phone' => '+212640000005', 'city' => 'Tangier', 'experience' => 6,
                'validation_status' => 'pending',
                'bio' => 'Street and documentary photographer capturing the raw beauty of everyday life in Tangier and northern Morocco.',
            ],
        ];

        $profiles = [];
        foreach ($data as $key => $info) {
            $user = $this->ensureUser($info['email'], $info['first_name'], $info['last_name'], $info['phone'], 'photographer');

            $profiles[$key] = PhotographerProfile::where('id_user', $user->id_user)->first()
                ?? PhotographerProfile::create([
                    'city'               => $info['city'],
                    'experience'         => $info['experience'],
                    'validation_status'  => $info['validation_status'],
                    'bio'                => $info['bio'],
                    'id_user'            => $user->id_user,
                ]);
        }

        return $profiles;
    }

    /* ── Services ───────────────────────────────── */

    private function ensureServices(array $profiles, array $cats): array
    {
        $defs = [
            ['ahmed',  'Wedding Full Day',   'Full-day wedding photography from preparation to reception. Includes edited high-resolution images.', 3500, 480, 'Wedding Photography'],
            ['ahmed',  'Event Coverage',     'Professional event photography for corporate events, parties, and celebrations.', 1500, 180, 'Event Photography'],
            ['fatima', 'Portrait Session',   'Studio or outdoor portrait session with professional lighting and retouching.', 800, 90, 'Portrait Photography'],
            ['fatima', 'Fashion Editorial',  'Fashion and editorial photography for models, brands, and publications.', 2000, 240, 'Portrait Photography'],
            ['karim',  'Cityscape Tour',     'Guided photography tour capturing the best cityscapes and urban views.', 1200, 180, 'Landscape Photography'],
            ['karim',  'Architecture Shoot', 'Professional architectural photography of buildings, interiors, and historic sites.', 1800, 240, 'Landscape Photography'],
            ['leila',  'Family Session',     'Relaxed family photography session in a location of your choice.', 600, 120, 'Family Photography'],
            ['leila',  'Newborn Shoot',      'Gentle and artistic newborn photography in a safe, comfortable setting.', 900, 90, 'Family Photography'],
            ['omar',   'Street Walk',        'Documentary-style street photography capturing everyday life and local culture.', 500, 120, 'Event Photography'],
        ];

        $services = [];
        foreach ($defs as [$photographer, $title, $desc, $price, $duration, $catName]) {
            $profile = $profiles[$photographer];
            $cat     = $cats[$catName];
            $key     = "{$photographer}_" . strtolower(str_replace(' ', '_', $title));

            $services[$key] = Service::where('id_profile', $profile->id_profile)
                ->where('title', $title)->first()
                ?? Service::create([
                    'title'       => $title,
                    'description' => $desc,
                    'price'       => $price,
                    'duration'    => $duration,
                    'id_profile'  => $profile->id_profile,
                    'id_category' => $cat->id_category,
                ]);
        }

        return $services;
    }

    /* ── Portfolio ──────────────────────────────── */

    private function ensurePortfolio(array $profiles): void
    {
        $descriptions = [
            'ahmed'  => ['Golden hour wedding ceremony at a traditional riad', 'Elegant bride and groom portrait session', 'Vibrant wedding reception celebration'],
            'fatima' => ['Natural light studio portrait', 'Fashion editorial in an urban setting', 'Creative portrait with dramatic lighting'],
            'karim'  => ['Panoramic view of the ancient medina', 'Modern architecture in the capital city', 'Coastal landscape at golden hour'],
            'leila'  => ['Joyful family gathering in the garden', 'Tender newborn photography session', 'Candid family moments outdoors'],
            'omar'   => ['Street life in the old quarter', 'Documentary style market scene', 'Candid urban moment'],
        ];

        $index = 1;
        foreach ($profiles as $key => $profile) {
            foreach ($descriptions[$key] as $desc) {
                $path = "portfolio/photo-{$index}.jpg";

                Portfolio::where('image', $path)->first()
                    ?? Portfolio::create([
                        'image'       => $path,
                        'description' => $desc,
                        'id_profile'  => $profile->id_profile,
                    ]);

                $index++;
            }
        }
    }

    /* ── Availabilities ─────────────────────────── */

    private function ensureAvailabilities(array $profiles): array
    {
        $defs = [
            'ahmed_0920'  => ['ahmed',  '2026-09-20', '09:00', '17:00'],
            'ahmed_0922'  => ['ahmed',  '2026-09-22', '14:00', '17:00'],
            'ahmed_0929'  => ['ahmed',  '2026-09-29', '14:00', '17:00'],
            'ahmed_1005'  => ['ahmed',  '2026-10-05', '09:00', '13:00'],
            'ahmed_1010'  => ['ahmed',  '2026-10-10', '14:00', '18:00'],
            'fatima_0923' => ['fatima', '2026-09-23', '10:00', '11:30'],
            'fatima_0930' => ['fatima', '2026-09-30', '09:00', '13:00'],
            'fatima_1006' => ['fatima', '2026-10-06', '14:00', '17:00'],
            'fatima_1012' => ['fatima', '2026-10-12', '10:00', '12:00'],
            'karim_0925'  => ['karim',  '2026-09-25', '09:00', '12:00'],
            'karim_1002'  => ['karim',  '2026-10-02', '14:00', '18:00'],
            'karim_1007'  => ['karim',  '2026-10-07', '09:00', '13:00'],
            'karim_1014'  => ['karim',  '2026-10-14', '15:00', '18:00'],
            'leila_0928'  => ['leila',  '2026-09-28', '10:00', '12:00'],
            'leila_1004'  => ['leila',  '2026-10-04', '09:00', '11:00'],
            'leila_1009'  => ['leila',  '2026-10-09', '14:00', '17:00'],
            'leila_1015'  => ['leila',  '2026-10-15', '10:00', '13:00'],
            'omar_1001'   => ['omar',   '2026-10-01', '09:00', '12:00'],
            'omar_1008'   => ['omar',   '2026-10-08', '14:00', '17:00'],
            'omar_1011'   => ['omar',   '2026-10-11', '10:00', '13:00'],
            'omar_1013'   => ['omar',   '2026-10-13', '09:00', '12:00'],
        ];

        $slots = [];
        foreach ($defs as $key => [$photographer, $date, $start, $end]) {
            $profile = $profiles[$photographer];

            $slots[$key] = Availability::where('id_profile', $profile->id_profile)
                ->where('available_date', $date)
                ->where('start_time', $start)
                ->where('end_time', $end)
                ->first()
                ?? Availability::create([
                    'available_date' => $date,
                    'start_time'     => $start,
                    'end_time'       => $end,
                    'id_profile'     => $profile->id_profile,
                ]);
        }

        return $slots;
    }

    /* ── Bookings & Reviews ─────────────────────── */

    private function ensureBookings(array $clients, array $services, array $slots): void
    {
        $defs = [
            // [client_key, service_key, slot_key, status, address, review?]
            ['sara',   'ahmed_wedding_full_day',   'ahmed_0920',  'completed', 'Riad Yasmine, Medina, Marrakech',
                ['rating' => 5, 'comment' => 'Ahmed captured our wedding perfectly. Every moment was beautifully documented from start to finish.']],
            ['sara',   'ahmed_event_coverage',     'ahmed_0922',  'completed', 'La Mamounia Hotel, Marrakech',
                ['rating' => 4, 'comment' => 'Great event coverage, very professional. Ahmed has an eye for capturing candid moments.']],
            ['youssef','fatima_portrait_session',  'fatima_0923', 'completed', 'Casa Marina, Casablanca',
                ['rating' => 5, 'comment' => 'Fatima made me feel so comfortable during the session. The portraits turned out amazing.']],
            ['nadia',  'karim_cityscape_tour',     'karim_0925',  'accepted',  'Kasbah of the Udayas, Rabat', null],
            ['sara',   'leila_family_session',     'leila_0928',  'pending',   'Jnan Sbil Gardens, Fes', null],
            ['youssef','ahmed_event_coverage',     'ahmed_0929',  'rejected',  'Four Seasons Resort, Marrakech', null],
            ['nadia',  'fatima_fashion_editorial', 'fatima_0930', 'cancelled', 'Corniche Ain Diab, Casablanca', null],
            ['sara',   'karim_architecture_shoot', 'karim_1002',  'completed', 'Hassan Tower, Rabat',
                ['rating' => 4, 'comment' => "Beautiful shots of Rabat's architecture. Karim really knows how to frame buildings."]],
        ];

        foreach ($defs as [$clientKey, $serviceKey, $slotKey, $status, $address, $review]) {
            $client      = $clients[$clientKey];
            $service     = $services[$serviceKey];
            $availability = $slots[$slotKey];

            $booking = Booking::where('id_user', $client->id_user)
                ->where('id_service', $service->id_service)
                ->where('id_availability', $availability->id_availability)
                ->first();

            if (! $booking) {
                $booking = Booking::create([
                    'booking_date'  => now(),
                    'event_date'    => $availability->available_date->format('Y-m-d'),
                    'event_address' => $address,
                    'total_price'   => $service->price,
                    'status'        => $status,
                    'id_user'       => $client->id_user,
                    'id_service'    => $service->id_service,
                    'id_availability' => $availability->id_availability,
                ]);
            }

            if ($review && $booking->status === 'completed') {
                $this->ensureReview($booking, $review['rating'], $review['comment']);
            }
        }
    }

    private function ensureReview(Booking $booking, int $rating, string $comment): void
    {
        if (Review::where('id_booking', $booking->id_booking)->exists()) {
            return;
        }

        $booking->load('service.photographerProfile');

        Review::create([
            'rating'     => $rating,
            'comment'    => $comment,
            'review_date' => now(),
            'id_user'    => $booking->id_user,
            'id_profile' => $booking->service->photographerProfile->id_profile,
            'id_booking' => $booking->id_booking,
        ]);
    }

    /* ── Portfolio Image Generation (PHP GD) ────── */

    private function generatePortfolioImages(): void
    {
        if (! function_exists('imagecreatetruecolor')) {
            $this->command?->warn('GD not available — skipping image generation.');
            return;
        }

        $palettes = [
            ['r' => 200, 'g' => 140, 'b' => 50],
            ['r' => 50,  'g' => 80,  'b' => 160],
            ['r' => 60,  'g' => 120, 'b' => 70],
            ['r' => 180, 'g' => 80,  'b' => 90],
            ['r' => 60,  'g' => 70,  'b' => 85],
        ];

        $dir = Storage::disk('public')->path('portfolio');

        for ($i = 1; $i <= 15; $i++) {
            $path = "{$dir}/photo-{$i}.jpg";
            if (file_exists($path)) {
                continue;
            }

            $palette = $palettes[intdiv($i - 1, 3)];
            $variant = ($i - 1) % 3;

            $this->createJpeg($path, $palette, $variant);
        }
    }

    private function createJpeg(string $path, array $palette, int $variant): void
    {
        $w = 800;
        $h = 600;
        $img = imagecreatetruecolor($w, $h);

        for ($y = 0; $y < $h; $y++) {
            $t = $y / $h;
            $r = (int) ($palette['r'] * (1 - $t * 0.4));
            $g = (int) ($palette['g'] * (1 - $t * 0.3));
            $b = (int) ($palette['b'] * (1 - $t * 0.2));
            $line = imagecolorallocate($img, $r, $g, $b);
            imageline($img, 0, $y, $w, $y, $line);
        }

        match ($variant) {
            0 => $this->drawBokeh($img, $palette),
            1 => $this->drawLines($img, $palette),
            2 => $this->drawBlocks($img, $palette),
        };

        imagejpeg($img, $path, 85);
        imagedestroy($img);
    }

    private function drawBokeh(\GdImage $img, array $p): void
    {
        $coords = [[150, 120, 80], [400, 200, 120], [650, 100, 60],
                   [250, 400, 100], [550, 350, 90], [100, 500, 50]];

        foreach ($coords as [$cx, $cy, $r]) {
            $c = imagecolorallocatealpha($img,
                min(255, $p['r'] + 60), min(255, $p['g'] + 60), min(255, $p['b'] + 60), 50);
            imagefilledellipse($img, $cx, $cy, $r * 2, $r * 2, $c);
        }
    }

    private function drawLines(\GdImage $img, array $p): void
    {
        $white = imagecolorallocatealpha($img, 255, 255, 255, 40);
        for ($i = 0; $i < 8; $i++) {
            $x    = (int) ($i * 114);
            $off  = ($i % 2 === 0) ? 100 : -100;
            imageline($img, $x, 0, $x + $off, 600, $white);
        }

        $accent = imagecolorallocatealpha($img,
            min(255, $p['r'] + 40), min(255, $p['g'] + 40), min(255, $p['b'] + 40), 35);
        imageline($img, 0, 200, 800, 200, $accent);
        imageline($img, 0, 400, 800, 400, $accent);
    }

    private function drawBlocks(\GdImage $img, array $p): void
    {
        $rects = [[50, 50, 200, 180], [300, 150, 500, 350],
                  [550, 50, 750, 220], [100, 300, 350, 500], [450, 380, 700, 550]];

        foreach ($rects as [$x1, $y1, $x2, $y2]) {
            $c = imagecolorallocatealpha($img,
                min(255, $p['r'] + 30), min(255, $p['g'] + 30), min(255, $p['b'] + 30), 55);
            imagefilledrectangle($img, $x1, $y1, $x2, $y2, $c);
        }
    }
}
