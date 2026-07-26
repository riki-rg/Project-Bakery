import { useState, useCallback, useRef } from 'react'
import { GoogleMap, useLoadScript, Marker, Autocomplete } from '@react-google-maps/api'
import { MapPin, Loader } from 'lucide-react'
import styles from './AddressMapPicker.module.css'

const libraries = ['places']

const mapContainerStyle = {
  width: '100%',
  height: '400px',
  borderRadius: 'var(--rounded-md)',
}

// Default center (Jakarta, Indonesia)
const defaultCenter = {
  lat: -6.2088,
  lng: 106.8456,
}

export function AddressMapPicker({ onLocationSelect, initialLocation }) {
  const { isLoaded, loadError } = useLoadScript({
    googleMapsApiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY || '',
    libraries,
  })

  const [markerPosition, setMarkerPosition] = useState(
    initialLocation || defaultCenter
  )
  const [mapCenter, setMapCenter] = useState(initialLocation || defaultCenter)
  const [selectedAddress, setSelectedAddress] = useState('')

  const autocompleteRef = useRef(null)
  const mapRef = useRef(null)

  const onMapLoad = useCallback((map) => {
    mapRef.current = map
  }, [])

  const onMapClick = useCallback(
    (e) => {
      const lat = e.latLng.lat()
      const lng = e.latLng.lng()
      
      setMarkerPosition({ lat, lng })
      
      // Reverse geocode to get address
      if (window.google) {
        const geocoder = new window.google.maps.Geocoder()
        geocoder.geocode({ location: { lat, lng } }, (results, status) => {
          if (status === 'OK' && results[0]) {
            const address = results[0].formatted_address
            setSelectedAddress(address)
            
            if (onLocationSelect) {
              onLocationSelect({
                lat,
                lng,
                address,
                addressComponents: parseAddressComponents(results[0].address_components),
              })
            }
          }
        })
      }
    },
    [onLocationSelect]
  )

  const onMarkerDragEnd = useCallback(
    (e) => {
      const lat = e.latLng.lat()
      const lng = e.latLng.lng()
      
      setMarkerPosition({ lat, lng })
      
      // Reverse geocode
      if (window.google) {
        const geocoder = new window.google.maps.Geocoder()
        geocoder.geocode({ location: { lat, lng } }, (results, status) => {
          if (status === 'OK' && results[0]) {
            const address = results[0].formatted_address
            setSelectedAddress(address)
            
            if (onLocationSelect) {
              onLocationSelect({
                lat,
                lng,
                address,
                addressComponents: parseAddressComponents(results[0].address_components),
              })
            }
          }
        })
      }
    },
    [onLocationSelect]
  )

  const onPlaceChanged = () => {
    if (autocompleteRef.current) {
      const place = autocompleteRef.current.getPlace()
      
      if (place.geometry) {
        const lat = place.geometry.location.lat()
        const lng = place.geometry.location.lng()
        const address = place.formatted_address
        
        setMarkerPosition({ lat, lng })
        setMapCenter({ lat, lng })
        setSelectedAddress(address)
        
        if (onLocationSelect) {
          onLocationSelect({
            lat,
            lng,
            address,
            addressComponents: parseAddressComponents(place.address_components),
          })
        }
      }
    }
  }

  const getCurrentLocation = () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const lat = position.coords.latitude
          const lng = position.coords.longitude
          
          setMarkerPosition({ lat, lng })
          setMapCenter({ lat, lng })
          
          // Reverse geocode
          if (window.google) {
            const geocoder = new window.google.maps.Geocoder()
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
              if (status === 'OK' && results[0]) {
                const address = results[0].formatted_address
                setSelectedAddress(address)
                
                if (onLocationSelect) {
                  onLocationSelect({
                    lat,
                    lng,
                    address,
                    addressComponents: parseAddressComponents(results[0].address_components),
                  })
                }
              }
            })
          }
        },
        (error) => {
          console.error('Error getting location:', error)
          alert('Tidak dapat mengakses lokasi. Pastikan izin lokasi diaktifkan.')
        }
      )
    } else {
      alert('Browser Anda tidak mendukung geolocation.')
    }
  }

  const parseAddressComponents = (components) => {
    const parsed = {
      street: '',
      district: '',
      city: '',
      province: '',
      postalCode: '',
      country: '',
    }

    components.forEach((component) => {
      const types = component.types
      
      if (types.includes('route')) {
        parsed.street = component.long_name
      }
      if (types.includes('sublocality_level_1') || types.includes('administrative_area_level_4')) {
        parsed.district = component.long_name
      }
      if (types.includes('administrative_area_level_2') || types.includes('locality')) {
        parsed.city = component.long_name
      }
      if (types.includes('administrative_area_level_1')) {
        parsed.province = component.long_name
      }
      if (types.includes('postal_code')) {
        parsed.postalCode = component.long_name
      }
      if (types.includes('country')) {
        parsed.country = component.long_name
      }
    })

    return parsed
  }

  if (loadError) {
    return (
      <div className={styles.error}>
        <p>Error loading Google Maps. Pastikan API key sudah dikonfigurasi.</p>
      </div>
    )
  }

  if (!isLoaded) {
    return (
      <div className={styles.loading}>
        <Loader className={styles.spinner} size={32} />
        <p>Loading map...</p>
      </div>
    )
  }

  return (
    <div className={styles.container}>
      <div className={styles.searchBar}>
        <Autocomplete
          onLoad={(autocomplete) => (autocompleteRef.current = autocomplete)}
          onPlaceChanged={onPlaceChanged}
        >
          <input
            type="text"
            placeholder="Cari alamat atau nama tempat..."
            className={styles.searchInput}
          />
        </Autocomplete>
        <button
          type="button"
          onClick={getCurrentLocation}
          className={styles.locationBtn}
          title="Gunakan lokasi saat ini"
        >
          <MapPin size={18} />
          Lokasi Saya
        </button>
      </div>

      {selectedAddress && (
        <div className={styles.selectedAddress}>
          <MapPin size={16} />
          <span>{selectedAddress}</span>
        </div>
      )}

      <GoogleMap
        mapContainerStyle={mapContainerStyle}
        zoom={15}
        center={mapCenter}
        onClick={onMapClick}
        onLoad={onMapLoad}
        options={{
          streetViewControl: false,
          mapTypeControl: false,
          fullscreenControl: false,
        }}
      >
        <Marker
          position={markerPosition}
          draggable={true}
          onDragEnd={onMarkerDragEnd}
        />
      </GoogleMap>

      <div className={styles.hint}>
        <p>💡 Klik di peta atau drag pin merah untuk menandai lokasi Anda</p>
      </div>
    </div>
  )
}
